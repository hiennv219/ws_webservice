<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute phải được đồng ý.',
    'active_url'           => ':attribute có giá trị không phải là đường dẫn.',
    'after'                => ':attribute phải có giá trị nằm sau ngày :date.',
    'after_or_equal'       => ':attribute phải có giá trị nằm sau hoặc bằng với ngày :date.',
    'alpha'                => ':attribute phải có giá trị chỉ gồm các chữ cái.',
    'alpha_dash'           => ':attribute phải có giá trị chỉ gồm chữ cái, chữ số, và dấu gạch ngang.',
    'alpha_num'            => ':attribute phải có giá trị chỉ gồm chữ cái và chữ số.',
    'array'                => ':attribute phải có giá trị là mảng các phần tử dữ liệu.',
    'before'               => ':attribute phải có giá trị nằm trước ngày :date.',
    'before_or_equal'      => ':attribute phải có giá trị nằm trước hoặc bằng với ngày to :date.',
    'between'              => [
        'numeric' => ':attribute phải có giá trị nằm giữa :min và :max.',
        'file'    => 'Tập tin :attribute phải có kích thước nằm giữa :min và :max KB.',
        'string'  => ':attribute phải có độ dài nằm giữa :min và :max ký tự.',
        'array'   => ':attribute phải có số lượng phần tử nằm giữa :min và :max.',
    ],
    'boolean'              => ':attribute phải có giá trị đúng hoặc sai.',
    'confirmed'            => ':attribute không xác nhận đúng giá trị.',
    'date'                 => ':attribute có giá trị không phải ngày tháng.',
    'date_format'          => ':attribute có giá trị không đúng với định dạng ngày tháng :format.',
    'different'            => ':attribute và :other phải có giá trị khác nhau.',
    'digits'               => ':attribute phải có giá trị là kiểu số với độ dài :digits.',
    'digits_between'       => ':attribute phải có giá trị là số với độ dài nằm giữa :min và :max.',
    'dimensions'           => ':attribute có kích thước ảnh không hợp lệ.',
    'distinct'             => ':attribute có giá trị bị lặp lại.',
    'email'                => ':attribute có giá trị không phải là địa chỉ thư điện tử hợp lệ.',
    'exists'               => ':attribute có giá trị được chọn không hợp lệ.',
    'file'                 => ':attribute phải là tập tin.',
    'filled'               => ':attribute không được để trống.',
    'image'                => ':attribute phải là tập tin hình ảnh.',
    'in'                   => ':attribute có giá trị được chọn không hợp lệ.',
    'in_array'             => ':attribute có giá trị không nằm trong :other.',
    'integer'              => ':attribute phải có giá trị là số nguyên.',
    'ip'                   => ':attribute phải có giá trị là địa chỉ IP hợp lệ.',
    'json'                 => ':attribute must có giá trị là chuỗi JSON hợp lệ.',
    'max'                  => [
        'numeric' => ':attribute phải có giá trị không lớn hơn :max.',
        'file'    => 'Tập tin :attribute phải có kích thước tập tin không lớn hơn :max KB.',
        'string'  => ':attribute phải có độ dài không lớn hơn :max ký tự.',
        'array'   => ':attribute phải có số lượng phần tử không lớn hơn :max.',
    ],
    'mimes'                => ':attribute phải là tập tin: :values.',
    'mimetypes'            => ':attribute phải là tập tin: :values.',
    'min'                  => [
        'numeric' => ':attribute phải có giá trị nhỏ nhất là :min.',
        'file'    => 'Tập tin :attribute phải có kích thước nhỏ nhất là :min KB.',
        'string'  => ':attribute phải có độ dài nhỏ nhất :min ký tự.',
        'array'   => ':attribute phải có số lượng phần tử nhỏ nhất là :min.',
    ],
    'not_in'               => ':attribute được chọn không hợp lệ.',
    'numeric'              => ':attribute phải có giá trị là số.',
    'present'              => ':attribute phải tồn tại.',
    'regex'                => ':attribute có giá trị với định dạng không hợp lệ.',
    'required'             => ':attribute không để trống.',
    'required_if'          => ':attribute không để trống khi :other có giá trị là :value.',
    'required_unless'      => ':attribute không để trống trừ trường hợp :other có các giá trị :values.',
    'required_with'        => ':attribute không để trống khi các giá trị :values được thiết lập',
    'required_with_all'    => ':attribute không để trống khi các giá trị :values được thiết lập.',
    'required_without'     => ':attribute không để trống khi các giá trị :values không được thiết lập.',
    'required_without_all' => ':attribute không để trống khi không có giá trị nào trong các giá trị :values được thiết lập.',
    'same'                 => ':attribute and :other must match.',
    'size'                 => [
        'numeric' => ':attribute phải có giá trị :size.',
        'file'    => 'Tập tin :attribute phải có kích thước bằng :size KB.',
        'string'  => ':attribute phải có độ dài bằng :size ký tự.',
        'array'   => ':attribute phải có :size phần tử.',
    ],
    'string'               => ':attribute có giá trị là chuỗi.',
    'timezone'             => ':attribute phải có giá trị là múi giờ hợp lệ.',
    'unique'               => ':attribute có giá trị đã tồn tại.',
    'uploaded'             => ':attribute gặp lỗi khi tải lên.',
    'url'                  => ':attribute có giá trị với định dạng đường dẫn không hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

    'password' => 'Mật khẩu hiện tại không đúng',
    're_captcha' => 'Captcha chưa được chọn'
];