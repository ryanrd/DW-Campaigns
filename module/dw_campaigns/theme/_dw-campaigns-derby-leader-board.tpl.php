<?php
    if(!is_null($campaign)) {
        $leaders = _dw_campaigns_campaign_leaders($campaign, 9999);
    } else {
        $leaders = _dw_campaigns_all_leaders(9999);
    }

    $num_per_page = 40;

    $headers        = array(
        array(
            'data'  => 'Position',
            'field' => 'position',
        ),
        array(
            'data'  => 'Name',
            'field' => 'name'
        ),
        array(
            'data'  => 'Location',
            'field' => 'location',
        ),
        array(
            'data'  => 'Donations',
            'field' => 'donations',
        ),
        array(
            'data'  => 'Total Raised',
            'field' => 'amount',
            'sort'  => 'desc'
        )
    );


    $query = "
        CREATE TEMPORARY TABLE
            leader_as_$num_per_page
            (
                name char(255),
                photo char(255),
                position int,
                amount float,
                location char(255),
                donations int,
                url char(255)
            )
    ";
    db_query($query);

    $position   = 0;
    $rows       = array();
    foreach($leaders as $leader) {
        $position++;
        $image_match    = '';
        $image_params   = array(
            'w'                 => 100,
            'contribution'      => true,
        );
        $fake_user      = user_load(array('uid' => $leader['drupal_id']));
        $contact        = $leader['contact'];

        $name           = $leader['name'];
        $photo          = _dw_campaigns_get_photo($fake_user, $image_params, 'user-photo', NULL, $image_match);
        $amount         = $leader['total'];
        $location       = $contact->city . ', ' . $contact->state_province;
        $donations      = $leader['donations'];
        $url            = $leader['url'];
        
        
        db_query("insert into {leader_as_$num_per_page} (name, photo, position, amount, location, donations, url) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $name, $photo, $position, $amount, $location, $donations, $url);
    }

    $sql_count = "select count(*) from leader_as_$num_per_page";

    //$result = db_query("select * from donations_as " . tablesort_sql($headers));
    $result = pager_query("select * from leader_as_$num_per_page " . tablesort_sql($headers), $num_per_page, 0, $sql_count);

    $rows = array();
    while ($db_row = db_fetch_object($result)) {
        $rows[] = array(
            'data' => array(
                array('data' => $db->position),
                array('data' => '<img src="' . $db_row->photo . '" width="25"> <a href="' . $db_row->url . '">' . $db_row->name . '</a>' ),
                array('data' => $db_row->location),
                array('data' => $db_row->donations),
                array('data' => dw_campaigns_force_decimal($db_row->amount)),
            )    
        );
    }

    echo theme('dw_campaigns_derby_statistics', $campaign, TRUE);
    echo theme('table', $headers, $rows);
    echo theme('pager', NULL, $num_per_page, 0);
