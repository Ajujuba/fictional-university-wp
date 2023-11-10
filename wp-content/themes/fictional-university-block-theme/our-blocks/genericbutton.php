<?php
    $text = array_key_exists("text", $attributes) && isset($attributes["text"]) ? $attributes["text"] : ""; //the $text variable is being set. The code checks whether the "text" key exists in the $attributes array and whether the value associated with this key is defined (i.e., not null). If both conditions are true, the value is assigned to the $text variable. Otherwise, the variable receives an empty string ""
    $size = array_key_exists("size", $attributes) && isset($attributes["size"]) ? $attributes["size"] : "large"; // checks whether the key "size" exists in $attributes and whether the value associated with that key is set. If both conditions are true, the value is assigned to the $size variable. Otherwise, the variable receives the default value "large"
    $colorName  = array_key_exists("colorName", $attributes) && isset($attributes["colorName"]) ? $attributes["colorName"] : "blue"; //checks whether the key "colorName" exists in $attributes and whether the value associated with that key is set. If both conditions are true, the value is assigned to the $colorName variable. Otherwise, the variable receives the default value "blue"
    $url = array_key_exists("linkObject", $attributes) && array_key_exists("url", $attributes["linkObject"]) ? $attributes["linkObject"]["url"] : "#"; //checks if the "linkObject" key exists in $attributes and then checks if the "url" key exists within the array associated with "linkObject". If both conditions are true, the value of "url" is assigned to the $url variable. Otherwise, the variable receives "#"
?>
<br>
<a style="margin: 25px 0;" href=<?php echo $url; ?> class="btn btn--<?php echo $size; ?> btn--<?php echo $colorName; ?>" >
    <?php echo $text;?>
</a>