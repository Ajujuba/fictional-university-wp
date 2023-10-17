<form class="search-form" action="index.php" method="get">
    <!-- The name is 's' because WP search per default 'localhost/yourproject?s=value' so, we'll reproduce this behavior -->
    <label class="headline headline--medium" for="s">Perform a new search:</label>
    <div class="search-form-row">
        <input class="s" type="search" id="s" name="s" placeholder="Search Here...">
        <input class="search-submit" type="submit" value="search">
    </div>
</form>