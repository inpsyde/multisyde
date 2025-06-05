<?php
/**
 * MultiSyde Modules Configuration
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules;

use Syde\MultiSyde\Modules\GetSiteBy\Feature as GetSiteBy;
use Syde\MultiSyde\Modules\GetSiteBy\About as GetSiteByInformation;
use Syde\MultiSyde\Modules\SiteActivePlugins\Feature as SiteActivePlugins;
use Syde\MultiSyde\Modules\SiteActivePlugins\About as SiteActivePluginsInformation;
use Syde\MultiSyde\Modules\LastUserLogin\Feature as LastUserLogin;
use Syde\MultiSyde\Modules\LastUserLogin\About as LastUserLoginInformation;

return array(
	GetSiteBy::class         => GetSiteByInformation::class,
	SiteActivePlugins::class => SiteActivePluginsInformation::class,
	LastUserLogin::class     => LastUserLoginInformation::class,
);
