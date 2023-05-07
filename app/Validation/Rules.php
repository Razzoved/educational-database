<?php declare(strict_types=1);

namespace App\Validation;

use App\Entities\Cast\StatusCast;
use App\Entities\Property;
use CodeIgniter\Validation\FormatRules;

/**
 * Custom ruleset for validation of requests.
 *
 * @author Jan Martinek
 */
class Rules
{
    /**
     * Checks if user's email is unique within the database. Takes into consideration
     * the possibility of it being the email of the user, which evaluates to true.
     *
     * @param string $email  The user's email.
     * @param array $data    The data of the request
     */
    public function user_unique_email(string $email, $ignored, array $data): bool
    {
        $id = $data['id'];

        $users = model(UserModel::class)->where('user_email', $email)->findAll(2);
        $count = sizeof($users);

        return $count === 0 || ($count === 1 && is_numeric($id) && $users[0]->id === (int) $id);
    }

    /**
     * Checks if the email address exists in the database.
     */
    public function user_email(string $email): bool
    {
        return model(UserModel::class)->where('user_email', $email)->first() !== null;
    }

    /**
     * Checks if the password is valid for the given user's email address.
     */
    public function user_password(string $password, string $email): bool
    {
        $user = model(UserModel::class)->where('user_email', $email)->first();
        return $user && password_verify($password, $user->password);
    }

    /**
     * Checks if the value is a valid StatusCast value.
     */
    public function valid_status(string $status) : bool
    {
        return StatusCast::isValid($status) || StatusCast::isValidIndex($status);
    }

    /**
     * Checks if the property value is a unique value under the given parent tag.
     * Checks if the property is the one being updated, in which case it's valid too.
     *
     * @param string $value  The property's value
     * @param array $data    The data of the request
     * @param ?string $error Pointer to error message
     */
    public function property_unique_value(string $value, $ignored, array $data, ?string &$error = null) : bool
    {
        $id = $data['id'] ?? null;
        $tag = $data['tag'] ?? null;

        if ($value === "") {
            $error = lang('Validation.property_unique_value') . ' value is required!';
            return false;
        }
        if (!is_numeric($tag)) {
            $error = lang('Validation.property_unique_value') . ' tag is not numeric!';
            return false;
        }

        $properties = model(PropertyModel::class)
            ->where('property_tag', (int) $tag)
            ->where('property_value', $value)
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
    public function property_tag(int $tag, $ignored, array $data, ?string &$error = null) : bool
    {
        $id = $data['id'];

        if (!isset($id) || !is_numeric($id)) {
            return false;
        }

        return !self::checkCyclic(
            $tag,
            model(PropertyModel::class)->getTreeRecursive(new Property(['id' => $id])),
            $error,
            lang('Validation.property_tag')
        );
    }

    /**
     * Helper method, checks if $id does not exists in the subtree of property.
     */
    private static function checkCyclic(int $id, Property $property, ?string &$error = null, string $prefix = '') : bool
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
     * Checks if link are a valid array of urls.
     */
    public function valid_links($links) : bool
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
     * Check if files are a valid array of 'rootPath' => 'filename'
     */
    public function valid_files($files, ?string &$error = null) : bool
    {
        if (!is_array($files)) {
            return false;
        }
        foreach ($files as $tmpPath => $value) {
            if ($value == "") {
                $error = lang('Validation.valid_files') . ' invalid FILENAME!';
                return false;
            }
            if (!file_exists(ROOTPATH . $tmpPath)) {
                $error = lang('Validation.valid_files') .
                    '<strong>[' . $tmpPath . ']</strong>' .
                    ' is not a valid file!';
                return false;
            }
        }
        return true;
    }

    /**
     * Checks if all items of array are numbers.
     */
    public function valid_related($relations) : bool
    {
        if (!is_array($relations)) {
            return false;
        }
        foreach ($relations as $r) {
            if (!is_numeric($r) || (is_numeric($r) && $r <= 0)) {
                return false;
            }
        }
        return true;
    }
}
