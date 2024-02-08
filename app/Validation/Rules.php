<?php

declare(strict_types=1);

namespace App\Validation;

use App\Models as Models;
use App\Entities as Entities;
use App\Entities\Cast as Casts;
use CodeIgniter\Files\File;
use CodeIgniter\Validation\FormatRules;

/**
 * Custom ruleset for validation of requests.
 *
 * @author Jan Martinek
 */
class Rules
{
    /**
     * Checks if user's email is unique within the database. A unique email for this purpose
     * is an email that either:
     *  - does not exist in database yet
     *  - exists exactly once and the 'id' matches
     *
     * @param string $email  The user's email.
     * @param ?string $id    The user's id.
     */
    public function user_unique_email(string $email, ?string $id): bool
    {
        $model = model(Models\UserModel::class);
        return (is_numeric($id))
            ? $model->find($id)->email === $email
            : empty($model->where('email', $email)->findAll(2));
    }

    /**
     * Checks if the email address exists in the database.
     */
    public function user_email(string $email): bool
    {
        return model(Models\UserModel::class)->where('email', $email)->first() !== null;
    }

    /**
     * Checks if the password is valid for the given user's email address.
     */
    public function user_password(string $password, string $email): bool
    {
        $user = model(Models\UserModel::class)->where('email', $email)->first();
        return $user && password_verify($password, $user->password);
    }

    /**
     * Checks if the value is a valid StatusCast value.
     */
    public function valid_status(string $status): bool
    {
        return Casts\StatusCast::isValid($status) || Casts\StatusCast::isValidIndex($status);
    }

    /**
     * Checks if the property value is a unique value under the given parent tag.
     * Checks if the property is the one being updated, in which case it's valid too.
     *
     * @param string $value  The property's value
     * @param array $data    The data of the request
     * @param ?string $error Pointer to error message
     */
    public function property_unique_value(string $value, $ignored, array $data, ?string &$error = null): bool
    {
        $id = $data['id'] ?? null;
        $tag = $data['tag'] ?? null;

        if ($value === "") {
            $error = sprintf('%s\nValue: %s', lang('Validation.property_unique_value'), lang('Errors.empty_string'));
            return false;
        }
        if (!is_numeric($tag)) {
            $error = sprintf('%s\nTag: %s', lang('Validation.property_unique_value'), lang('Errors.not_a_number'));
            return false;
        }

        $properties = model(Models\PropertyModel::class)
            ->where('parent', (int) $tag)
            ->where('value', $value)
            ->findAll(2);
        $count = sizeof($properties);

        return $count === 0 || ($count === 1 && $properties[0]->id === (int) $id);
    }

    /**
     * Checks if the tag is valid and the property's id is not in cyclic dependency.
     *
     * @param int $tag       The property's tag
     * @param array $data    The data of the request
     * @param ?string $error Pointer to error message
     */
    public function property_tag(int $tag, $ignored, array $data, ?string &$error = null): bool
    {
        $id = $data['id'];

        if (!isset($id) || is_null($id) || $id === "" || $tag === 0) {
            return true;
        }

        if (!is_numeric($id)) {
            $error = sprintf(
                '%s\n%s: %s',
                lang('Validation.property_tag'),
                lang('Errors.invalid_id'),
                lang('Errors.not_a_number')
            );
            return false;
        }

        return !self::checkCyclic(
            $tag,
            model(Models\PropertyModel::class)->asTreeFrom(new Entities\Property(['id' => $id])),
            $error,
            lang('Validation.property_tag')
        );
    }

    /**
     * Helper method, checks if $id does not exists in the subtree of property.
     */
    private static function checkCyclic(int $id, Entities\Property $property, ?string &$error = null, string $prefix = ''): bool
    {
        if ($property->id === $id) {
            $error = "{$prefix}<br>[{$property->value}]";
            return true;
        }
        foreach ($property->children as $child) {
            $error = "{$prefix}<br>[{$child->value}]";
            if (self::checkCyclic($id, $child)) return true;
        }
        return false;
    }

    /**
     * Checks if the given value is an array of urls.
     */
    public function valid_links($links): bool
    {
        if (!is_array($links)) {
            return false;
        }
        $rules = new FormatRules();
        foreach ($links as $value) {
            if (!$rules->valid_url_strict($value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the given value is an array of 'rootPath' => 'filename' values
     */
    public function valid_files($files, ?string &$error = null): bool
    {
        if (!is_array($files)) {
            return false;
        }
        foreach ($files as $tmpPath => $value) {
            if ($value == "") {
                $error = sprintf('%s\n%s', lang('Validation.valid_files'), lang('Errors.empty_string'));
                return false;
            }
            if (!file_exists(ROOTPATH . $tmpPath)) {
                $error = sprintf(
                    '%s\n%s -> <strong>%s</strong>',
                    lang('Validation.valid_files'),
                    lang('Errors.not_found'),
                    $tmpPath
                );
                return false;
            }
        }
        return true;
    }

    /**
     * Check if given path is a valid relative path to an image.
     */
    public function valid_image(string $imagePath): bool
    {
        $file = new File(ROOTPATH . $imagePath);
        if (!isset($imagePath) || !$file->getRealPath()) {
            return false;
        }
        if (mb_strpos($file->getMimeType(), 'image/') === false) {
            return false;
        }
        return true;
    }

    /**
     * Checks if all items of array are numbers.
     */
    public function valid_related($relations): bool
    {
        if (!is_array($relations)) {
            return false;
        }
        foreach ($relations as $id => $title) {
            if (!is_numeric($id) || (is_numeric($id) && $id <= 0)) {
                return false;
            }
        }
        return true;
    }

    public function null_only($input): bool
    {
        return !isset($input) || $input === null;
    }
}
