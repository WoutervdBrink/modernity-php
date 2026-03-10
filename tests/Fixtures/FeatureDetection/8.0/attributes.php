#Test
Description: Attributes on classes were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
#[Example]
class foo {}
?>


#Test
Description: Attributes on methods were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
class bar {
    #[Example]
    function method_with_attribute() {

    }
}
?>


#Test
Description: Attributes on functions were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
#[Example]
function function_with_attribute() {

}
?>


#Test
Description: Attributes on parameters were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
function foo(#[Example] $parameter_with_attribute) {

}
?>


#Test
Description: Attributes on properties were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
class baz {
    #[Example]
    public $property_with_attribute;
}
?>


#Test
Description: Attributes on class constants were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
class example
{
    #[Example]
    const CONSTANT_WITH_ATTRIBUTE = 3;
}
?>


#Test
Description: Attribute groups on classes were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
#[Example, Another]
class foo {}
?>


#Test
Description: Attribute groups on methods were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
class bar {
    #[Example, Another]
    function method_with_attribute() {

    }
}
?>


#Test
Description: Attribute groups on functions were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
#[Example, Another]
function function_with_attribute() {

}
?>


#Test
Description: Attribute groups on parameters were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
function foo(#[Example, Another] $parameter_with_attribute) {

}
?>


#Test
Description: Attribute groups on properties were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
class baz {
    #[Example, Another]
    public $property_with_attribute;
}
?>


#Test
Description: Attribute groups on class constants were added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
class example
{
    #[Example,Another]
    const CONSTANT_WITH_ATTRIBUTE = 3;
}
?>
