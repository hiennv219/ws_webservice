/**
 * Created by Nguyen Tuan Linh on 2016-12-05.
 */
function NumberFormatHelper() {
    this.DEFAULT_NUMBER_OF_DECIMAL_POINTS = 2;

    this.type = KATNISS_SETTINGS.number_format;
    this.numberOfDecimalPoints = this.DEFAULT_NUMBER_OF_DECIMAL_POINTS;
}
NumberFormatHelper.prototype.modeInt = function (numberOfDecimalPoints) {
    this.mode(0);
};
NumberFormatHelper.prototype.modeNormal = function (numberOfDecimalPoints) {
    this.mode(this.DEFAULT_NUMBER_OF_DECIMAL_POINTS);
};
NumberFormatHelper.prototype.mode = function (numberOfDecimalPoints) {
    this.numberOfDecimalPoints = numberOfDecimalPoints;
};
NumberFormatHelper.prototype.format = function (number) {
    number = parseFloat(number);
    switch (this.type) {
        case 'point_comma':
            return this.formatPointComma(number);
        case 'point_space':
            return this.formatPointSpace(number);
        case 'comma_point':
            return this.formatCommaPoint(number);
        case 'comma_space':
            return this.formatCommaSpace(number);
        default:
            return number;
    }
};
NumberFormatHelper.prototype.formatPointComma = function (number) {
    return number.toFixed(this.numberOfDecimalPoints).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
};
NumberFormatHelper.prototype.formatPointSpace = function (number) {
    return number.toFixed(this.numberOfDecimalPoints).replace(/(\d)(?=(\d{3})+\.)/g, '$1 ');
};
NumberFormatHelper.prototype.formatCommaPoint = function (number) {
    number = this.formatPointSpace(number);
    return number.replace('.', ',').replace(' ', '.');
};
NumberFormatHelper.prototype.formatCommaSpace = function (number) {
    number = this.formatPointSpace(number);
    return number.replace('.', ',');
};