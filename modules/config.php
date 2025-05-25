<?php
/**
 * Multisyde Modules Configuration
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\Multisyde\Modules;

use Syde\Multisyde\Modules\GetSiteBy\Feature as GetSiteBy;
use Syde\Multisyde\Modules\GetSiteBy\About as GetSiteByInformation;
use Syde\Multisyde\Modules\SiteActivePlugins\Feature as SiteActivePlugins;
use Syde\Multisyde\Modules\SiteActivePlugins\About as SiteActivePluginsInformation;

return array(
	GetSiteBy::class         => GetSiteByInformation::class,
	SiteActivePlugins::class => SiteActivePluginsInformation::class,
);
