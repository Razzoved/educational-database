String.prototype.fill = function (data = undefined) {
  let result = this;
  for (var k in data) {
    result = result.replaceAll(`@${k}@`, data[k]);
  }
  return result;
};

String.prototype.html = function (data = undefined) {
  let result = this.fill(data);
  if ((match = result.match(/@[^ @]*@/)) !== null) {
    console.warn("Template not fully filled, please check your arguments");
    console.debug("Found: ", match);
  }
  const parser = new DOMParser();
  return parser.parseFromString(result, "text/html")?.body.firstElementChild;
};

HTMLInputElement.prototype.verifyOption = function () {
  const result = Array.from(this.list.querySelectorAll("option")).filter(
    (option) => option.value === this.value
  );
  return result.length > 0 && result[0];
};

console.log("Loaded common.js");
