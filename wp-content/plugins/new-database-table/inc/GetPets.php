<?php

class GetPets{
    function __construct()
    {
        global $wpdb;
        $tablename = $wpdb->prefix . 'pets';

        $this->args = $this->getArgs(); //Calls the getArgs() function to obtain the URL's GET parameters and store them in $this->args.
        $this->placeholders = $this->createPlaceholders(); //We call the createPlaceholders() function to create an array of placeholder values to be used in prepared queries.

        #Query to all results
        $query = "SELECT * FROM $tablename ";
        $query .= $this->createWhereText(); //Adds a WHERE clause to the query, based on parameters passed via URL.
        $query .= " LIMIT 50 ";
        $this->pets = $wpdb->get_results($wpdb->prepare($query, $this->placeholders)); //get_results() is used to execute a SQL query against the database and retrieve multiple records (rows) as a result.

        #Query to count the results
        $countQuery = "SELECT COUNT(*) FROM $tablename ";
        $countQuery .= $this->createWhereText();
        $this->count = $wpdb->get_var($wpdb->prepare($countQuery, $this->placeholders)); //get_var() is used to execute a SQL query against the database and retrieve a single value as a result.
    }

    #obtain the URL's GET parameters
    function getArgs(){
        $temp = [];
        
        #checks whether each specific GET parameter exists. If it does, it is processed and stored in the $temp array using the sanitize_text_field() function.
        if (isset($_GET['favcolor']) && !empty($_GET['favcolor'])) $temp['favcolor'] = sanitize_text_field($_GET['favcolor']);
        if (isset($_GET['species'])  && !empty($_GET['species'])) $temp['species'] = sanitize_text_field($_GET['species']);
        if (isset($_GET['minyear'])  && !empty($_GET['minyear'])) $temp['minyear'] = sanitize_text_field($_GET['minyear']);
        if (isset($_GET['maxyear'])  && !empty($_GET['maxyear'])) $temp['maxyear'] = sanitize_text_field($_GET['maxyear']);
        if (isset($_GET['minweight']) && !empty($_GET['minweight'])) $temp['minweight'] = sanitize_text_field($_GET['minweight']);
        if (isset($_GET['maxweight'])  && !empty($_GET['maxweight'])) $temp['maxweight'] = sanitize_text_field($_GET['maxweight']);
        if (isset($_GET['favhobby'])  && !empty($_GET['favhobby'])) $temp['favhobby'] = sanitize_text_field($_GET['favhobby']);
        if (isset($_GET['favfood'])  && !empty($_GET['favfood'])) $temp['favfood'] = sanitize_text_field($_GET['favfood']);
     
        return $temp;
    }

    #create an array of placeholder values
    function createPlaceholders(){
        #The function uses array_map() to traverse the $this->args array (which contains the GET parameters) and for each value, it simply returns the value itself. This creates an array of placeholder values. For example, if $this->args contains ['favcolor' => 'red', 'species' => 'dog'], the createPlaceholders() function returns ['red' , 'dog'].
        return array_map(function($x){
            return $x;
        }, $this->args);
    }

    #Create a WHERE clause to the query
    function createWhereText() {
        $whereQuery = "";

        if (count($this->args)) {
            $whereQuery = "WHERE ";
        }
    
        $currentPosition = 0;
        foreach($this->args as $index => $item) {
            //For each parameter, it calls the function specificQuery($index) to get the specific part of the WHERE clause related to that parameter and adds it to $whereQuery.
            $whereQuery .= $this->specificQuery($index);

            //checks that the current parameter is not the last one to avoid unnecessary addition of "AND" at the end of the WHERE clause.
            if ($currentPosition != count($this->args) - 1) {
                $whereQuery .= " AND ";
            }
            $currentPosition++;
        }
    
        return $whereQuery;
    }
    
    #This function takes a parameter $index, which is a key of the $this->args array, and returns a specific part of the WHERE clause of the SQL query based on this parameter. It uses a switch statement to determine which WHERE condition to apply based on the parameter name
    function specificQuery($index) {
        switch ($index) {
            case "minweight":
                return "petweight >= %d";
            case "maxweight":
                return "petweight <= %d";
            case "minyear":
                return "birthyear >= %d";
            case "maxyear":
                return "birthyear <= %d";
            default:
                return $index . " = %s";
        }
    }
}
