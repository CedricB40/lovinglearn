<?php

namespace App\Model;

use App\Entity\Theme;

class SearchData
{
    public int $page = 1;
    public string $q = '';
    public ?Theme $theme = null;
}