# The templating system

## Number filters

* **currency**
* **number**

## Text filters

* **format**: Formats text in a simple way.
* **unlink**: Strip links.

## Date and Time filters

* **date**: to get formatted date values
* **datetime**: to get formatted date and time values

### Patterns

* **d**: Short date pattern
* **D**: Long date pattern
* **p**: Medium date pattern
* **P**: Full date pattern
* **t**: Short time pattern
* **T**: Long time pattern
* **q**: Medium time pattern
* **Q**: Full time pattern
* **f**: Format date and time with long date pattern and short time pattern
* **F**: Format date and time with long date pattern and long time pattern
* **g**: Format date and time with short date pattern and short time pattern
* **G**: Format date and time with short date pattern and long time pattern
* **i**: Format as 'yyyy-MM-dd'
* **I**: Format as 'yyyy-MM-dd HH:mm:ss'
* **M** or **m**: Format as 'MMMM dd'
* **R** or **r**: Format as 'EEE, dd MMM yyyy HH:mm:ss'
* **s**: Format as 'yyyy-MM-ddTHH:mm:ss'
* **u**: Format as 'yyyy-MM-dd HH:mm:ss z'
* **U**: Format as 'EEEE dd MMMM yyyy HH:mm:ss'
* **Y** or **y**: Format as 'yyyy MMMM'
* **custom pattern**: See previous patterns to get an idea

## Other filters

* **count**: If data is an array it returns its length
* **unhttp**: Removes http(s):// from text