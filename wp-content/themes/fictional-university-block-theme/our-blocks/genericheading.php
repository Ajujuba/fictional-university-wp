<?php
    $text = array_key_exists("text", $attributes) && isset($attributes["text"]) ? $attributes["text"] : "";
    $size = array_key_exists("size", $attributes) && isset($attributes["size"]) ? $attributes["size"] : "large";
    
    if($size == "large"){
        $sizeHTML = "h1";
    }else if($size == "medium"){
        $sizeHTML = "h2";
    }else if($size == "small"){
        $sizeHTML = "h3";
    }else{
        $sizeHTML = "h1";
    }

?>
<<?php echo $sizeHTML; ?> class="headline headline--<?php echo $size; ?>">
    <?= $text?> 
</ <?= $sizeHTML?>>
