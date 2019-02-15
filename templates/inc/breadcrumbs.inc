<?php

$output = '';
if (!empty($data['breadcrumbs'])) {

    $output .= '<div class="bottom-border crumb">';

    $i = 0;
    foreach($data['breadcrumbs'] as $url => $crumb) {
        $i++;

        if ($i == count($data['breadcrumbs'])) {
            $output .= "<span>$crumb</span>";
        } else {
            $output .= '<a href="' . $url . '">' . $crumb . ' > </a>';

            if ($i == count($data['breadcrumbs'])-1) {
                $back = $url;
            }
        }
    }

    $output .= "<div class=\"pull-right\"><a href='$back'>< Back</a></div></div>";
}

echo $output;