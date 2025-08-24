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
use Syde\MultiSyde\Modules\SiteActiveTheme\Feature as SiteActiveTheme;
use Syde\MultiSyde\Modules\SiteActiveTheme\About as SiteActiveThemeInformation;
use Syde\MultiSyde\Modules\SitesDataViews\Feature as SitesDataViews;
use Syde\MultiSyde\Modules\SitesDataViews\About as SitesDataViewsInformation;

return array(
	GetSiteBy::class         => GetSiteByInformation::class,
	LastUserLogin::class     => LastUserLoginInformation::class,
	SiteActivePlugins::class => SiteActivePluginsInformation::class,
	SiteActiveTheme::class   => SiteActiveThemeInformation::class,
	SitesDataViews::class    => SitesDataViewsInformation::class,
);
