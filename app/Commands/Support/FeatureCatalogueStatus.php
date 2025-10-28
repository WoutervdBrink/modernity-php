<?php

namespace App\Commands\Support;

enum FeatureCatalogueStatus: string
{
    case IS_ABSTRACT = 'Abstract class';
    case NOT_IMPLEMENTED = 'Not implemented';
    case IMPLEMENTED = 'Implemented';
    case SUPERCLASS_IMPLEMENTED = 'Implemented via superclass(es)';
}
