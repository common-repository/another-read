<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
class Another_Read_Post_Creator{

    private static function arrayKeyCheck($key){
        if(isset($key)){
           return $key;
        }
        else{
            return '';
        }
    }

    public static function createActivity(){

        $settings = Another_Read_Settings::get_settings();
        $data = array(
            "accesskey" => $settings['api_key'],
            "quantityofrecords" => $settings['activity']['results'],
        );

        if($settings['activity']['publisher'] !== ''){
            $data['publisher'] = $settings['activity']['publisher'];
        }
        if($settings['activity']['contributor'] !== ''){
            $data['contributors'] = $settings['activity']['contributor'];
        }
        if($settings['activity']['keyword'] !== ''){
            $data['keywords'] = $settings['activity']['keyword'];
        }

        if(isset($settings['api_key']) && $settings['api_key'] !== ''){

            $numberOfResults = $settings['activity']['results'];

            $activityPayload = Another_Read_Api::api_call('activity', $data );
            $i = $numberOfResults - 1;

            if($activityPayload['ApiCallWasSuccessful'] == true){

                $activityPayload = $activityPayload['Payload'];

                if(!empty($activityPayload['Result']) ){
                
                    while($i >= 0 ){
                        if( get_post($activityPayload['Result'][$i]['ActivityID']) == false){

                            $activities = self::arrayKeyCheck($activityPayload['Result'][$i]);
                            $contributorID = self::arrayKeyCheck($activities['ContributorList'][0]);

                            $title = self::arrayKeyCheck($activities['ActivityText']);
                            $activityID = self::arrayKeyCheck($activities['ActivityID']);
                            $jacketImage = self::arrayKeyCheck($activities['ActivityJacketImage']);

                            $activityDate = self::arrayKeyCheck($activities['ActivityDate']);
                            $timestamp = strtotime($activityDate);
                            $activityDate = gmdate('jS F Y', $timestamp);

                            $bookISBN = $activities['Isbn'];


                            $bookLookup = self::arrayKeyCheck($activityPayload['BookLookup'][$bookISBN]);
                            $keynote = self::arrayKeyCheck($bookLookup['Keynote']);
                            $bookName = self::arrayKeyCheck($bookLookup['Title']);
                            $bookLink = self::arrayKeyCheck($bookLookup['BookLink']);

                            $contributorLookup = self::arrayKeyCheck($activityPayload['ContributorLookup'][$contributorID]);
                            $authorName = self::arrayKeyCheck($contributorLookup['DisplayName']);
                            $authorLink = self::arrayKeyCheck($contributorLookup['ContributorLink']);


                            $metaInput = array(
                                '_activity_content' => array(
                                    'activity_id' => $activityID,
                                    'jacket_image' => $jacketImage,
                                    'keynote' => $keynote,
                                    'activity_date' => $activityDate,
                                    'book_isbn' => $bookISBN,
                                    'book_name' => $bookName,
                                    'book_link' => $bookLink,
                                    'author_name' => $authorName,
                                    'author_link' => $authorLink
                                )

                            );

                            $activityPost = array(
                                'post_title'    => wp_strip_all_tags( $title ),
                                'post_status'   => 'publish',
                                'post_type'     => 'activity',
                                'meta_input'    => $metaInput,
                                'import_id'     => $activityID
                            );
                            
                            wp_insert_post($activityPost);
                        }
                        $i--;
                    }
                    return true;
                }
                else{
                    return 'no_results';
                }
            }
            else{
                return 'api_error';
            }
        }
        else{
            return 'no_api_key';
        }
    }

    public static function createStacks(){

        $settings = Another_Read_Settings::get_settings();
        $options = $settings['stacks'];
        $data = array(
            'accesskey' => $settings['api_key'],
            'usertoken' => $options['user_token'],
            'pagenumber' => 1,
            'pagesize' => 10,
            'includebooks' => 'true',
            'includeratings' => 'true',
        );

        $stackPayload = Another_Read_Api::api_call("stacks", $data);


        if(isset($settings['api_key']) && $settings['api_key'] !== ''){

            if($stackPayload['ApiCallWasSuccessful'] == true){

                $stackResults = $stackPayload['Payload']['Result']['Results'];

                foreach($stackResults as $stack ){
                    if( get_post($stack['StackID']) == false){

                        $stackResult = $stackPayload['Payload'];

                        $title = self::arrayKeyCheck($stack['Title']);
                        $stackID = self::arrayKeyCheck($stack['StackID']);
                        $bookList = self::arrayKeyCheck($stack['BookList']);

                        $metaInput = array(
                            '_stack_content' => array(
                                'title' => $title,
                                'stack_id' => $stackID,
                                'book_list' => array()
                            ),
                        );

                        $i = 0;
                        foreach($bookList as $book){

                            $bookLookup = self::arrayKeyCheck($stackResult['BookLookup'][$book]);
                            $jacketImage = self::arrayKeyCheck($bookLookup['JacketUrl']);
                            $keynote = self::arrayKeyCheck($bookLookup['Keynote']);
                            $bookName = self::arrayKeyCheck($bookLookup['Title']);
                            $bookLink = self::arrayKeyCheck($bookLookup['BookLink']);
                            $contributors = self::arrayKeyCheck($bookLookup['Contributors']);
                            $contributor = array();

                            $j = 0;
                            foreach($contributors as $contributorID){
                                $contributorLookup = self::arrayKeyCheck($stackResult['ContributorLookup'][$contributorID]);
                                $authorName = self::arrayKeyCheck($contributorLookup['DisplayName']);
                                $authorLink = self::arrayKeyCheck($contributorLookup['ContributorLink']);

                                $contributor[$j] = array(
                                    'author_name' => $authorName,
                                    'author_link' => $authorLink
                                );
                                
                                $j++;

                            }

                            $book = array(
                                'jacket_image' => $jacketImage,
                                'book_isbn' => $book,
                                'book_name' => $bookName,
                                'book_link' => $bookLink,
                                'keynote' => $keynote,
                                'contributors' => $contributor,
                            );



                            $metaInput['_stack_content']['book_list'][$i] = $book;
                            $i++;
                        }

                        $stackPost = array(
                            'post_title'    => wp_strip_all_tags( $title ),
                            'post_status'   => 'publish',
                            'post_type'     => 'stacks',
                            'meta_input'    => $metaInput,
                            'import_id'     => $stackID
                        );
                        
                        wp_insert_post($stackPost);

                    }

                }
                return true;
            }
            else{
                return 'api_error';
            }
        }
        else{
            return 'no_api_key';
        }
    }
}


?>