<?php

namespace Bence;


class Breadcrumbs
{
    public function getAccountBreadcrumbs(User $user, $uid, $breadcrumbs = array()) {
        $rawBreadcrumbs = $this->calculateAccountBreadcrumbs($uid, $breadcrumbs);
        return $this->formatAccountBreadcrumbArray($rawBreadcrumbs, $user);
    }

    private function calculateAccountBreadcrumbs($uid, $breadcrumbs) {
        $k = array_search($uid, $breadcrumbs);
        if ($k !== FALSE) {
            $breadcrumbs = array_slice($breadcrumbs, 0, ++$k, TRUE);
        } else {
            array_push($breadcrumbs, $uid);
        }
        return $breadcrumbs;
    }

    private function formatAccountBreadcrumbArray($rawBreadcrumbs, $user) {
        foreach($rawBreadcrumbs as $uid) {
            $thisUser = $user->getUserWithId($uid);
            $breadcrumbs['/account?uid=' . $thisUser['id']] = $thisUser['name'];
        }

        return $breadcrumbs;
    }
}