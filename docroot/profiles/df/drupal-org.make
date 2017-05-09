core = 8.x
api = 2

; Defaults
defaults[projects][subdir] = "contrib"

; Profiles
projects[lightning][type] = "profile"
projects[lightning][version] = "2.12"
projects[lightning][subdir] = ""

; Modules
projects[acquia_connector][type] = "module"
projects[acquia_connector][version] = "1.10"

projects[address][type] = "module"
projects[address][version] = "1.0-rc4"

projects[addtoany][type] = "module"
projects[addtoany][version] = "1.7"
projects[addtoany][patch][] = "https://www.drupal.org/files/issues/addtoany-revision-2855283-2.patch"

projects[admin_toolbar][type] = "module"
projects[admin_toolbar][version] = "1.19"

projects[adminimal_admin_toolbar][type] = "module"
projects[adminimal_admin_toolbar][version] = "1.3"
projects[adminimal_admin_toolbar][patch][] = "https://www.drupal.org/files/issues/2826670-adminimal-admin-toolbar-outside-in-styles.patch"

projects[better_formats][type] = "module"
projects[better_formats][download][type] = "git"
projects[better_formats][download][branch] = "8.x-1.x"
projects[better_formats][version] = "1.x-dev"

projects[block_class][type] = "module"
projects[block_class][download][type] = "git"
projects[block_class][download][branch] = "8.x-1.x"
projects[block_class][version] = "1.x-dev"
projects[block_class][patch][] = "https://www.drupal.org/files/issues/add-page-manager-support-2509142-2.patch"

projects[config_devel][type] = "module"
projects[config_devel][version] = "1.0-alpha3"

projects[config_provider][type] = "module"
projects[config_provider][version] = "1.0-beta4"

projects[config_rewrite][type] = "module"
projects[config_rewrite][version] = "1.0"

projects[config_sync][type] = "module"
projects[config_sync][download][type] = "git"
projects[config_sync][download][branch] = "8.x-1.x"
projects[config_sync][version] = "1.x-dev"

projects[config_tools][type] = "module"
projects[config_tools][download][type] = "git"
projects[config_tools][download][branch] = "8.x-1.x"
projects[config_tools][version] = "1.x-dev"

projects[config_update][type] = "module"
projects[config_update][version] = "1.3"

projects[conflict][type] = "module"
projects[conflict][version] = "1.0-alpha1"

projects[contact_message_rest][type] = "module"
projects[contact_message_rest][version] = "1.0-alpha1"

projects[contact_storage][type] = "module"
projects[contact_storage][version] = "1.0-beta8"
projects[contact_storage][patch][] = "https://www.drupal.org/files/issues/contact-storage-action-namepsace-2834490-6.patch"

projects[content_browser][type] = "module"
projects[content_browser][version] = "1.0-alpha4"
projects[content_browser][patch][] = "https://www.drupal.org/files/issues/content-browser-respect-type-2851687-5.patch"

projects[crop][type] = "module"
projects[crop][version] = "1.2"

projects[ctools][type] = "module"
projects[ctools][version] = "3.0-alpha27"
projects[ctools][patch][] = "https://www.drupal.org/files/issues/ctools-unserialize-plugin-collection-2831521-1.patch"

projects[diff][type] = "module"
projects[diff][version] = "1.0-rc1"

projects[dropzonejs][type] = "module"
projects[dropzonejs][version] = "1.0-alpha6"
projects[dropzonejs][patch][] = "https://www.drupal.org/files/issues/2868449-2.patch"

projects[embed][type] = "module"
projects[embed][version] = "1.0"

projects[entity][type] = "module"
projects[entity][version] = "1.0-alpha4"

projects[entity_block][type] = "module"
projects[entity_block][version] = "1.0-alpha2"
projects[entity_block][patch][] = "https://www.drupal.org/files/issues/add-uuid-support-2834377-2.patch"
projects[entity_block][patch][] = "https://www.drupal.org/files/issues/entity_block-view-builder-class.patch"

projects[entity_browser][type] = "module"
projects[entity_browser][version] = "1.0"
projects[entity_browser][patch][] = "https://www.drupal.org/files/issues/entity-browser-file-widget-single.patch"
projects[entity_browser][patch][] = "https://www.drupal.org/files/issues/2845037-4.patch"
projects[entity_browser][patch][] = "https://www.drupal.org/files/issues/entity-browser-quickedit-2733605-6.patch"
projects[entity_browser][patch][] = "https://www.drupal.org/files/issues/entity-browser-view-context-2865928-8.patch"

projects[entity_embed][type] = "module"
projects[entity_embed][version] = "1.0-beta2"
projects[entity_embed][patch][] = https://www.drupal.org/files/issues/2832504-2.patch

projects[entity_gallery][type] = "module"
projects[entity_gallery][download][type] = "git"
projects[entity_gallery][download][branch] = "8.x-1.x"
projects[entity_gallery][version] = "1.x-dev"

projects[features][type] = "module"
projects[features][version] = "3.5"

projects[field_formatter_class][type] = "module"
projects[field_formatter_class][version] = "1.0"

projects[field_group][type] = "module"
projects[field_group][version] = "1.0-rc6"

projects[file_entity][type] = "module"
projects[file_entity][version] = "2.0-beta3"

projects[geocoder][type] = "module"
projects[geocoder][version] = "2.0-alpha5"

projects[geofield][type] = "module"
projects[geofield][download][type] = "git"
projects[geofield][download][branch] = "8.x-1.x"
projects[geofield][version] = "1.x-dev"

projects[geolocation][type] = "module"
projects[geolocation][download][type] = "git"
projects[geolocation][download][branch] = "8.x-1.x"
projects[geolocation][version] = "1.x-dev"

projects[geophp][type] = "module"
projects[geophp][download][type] = "git"
projects[geophp][download][branch] = "8.x-1.x"
projects[geophp][version] = "1.x-dev"

projects[google_analytics][type] = "module"
projects[google_analytics][version] = "2.1"

projects[image_effects][type] = "module"
projects[image_effects][version] = "1.0"

projects[image_widget_crop][type] = "module"
projects[image_widget_crop][version] = "2.0"

projects[import][type] = "module"
projects[import][version] = "2.0-beta2"

projects[inline_entity_form][type] = "module"
projects[inline_entity_form][version] = "1.0-beta1"

projects[key_value][type] = "module"
projects[key_value][version] = "1.0"

projects[layout_plugin][type] = "module"
projects[layout_plugin][version] = "1.0-alpha23"

projects[leaflet][type] = "module"
projects[leaflet][download][type] = "git"
projects[leaflet][download][branch] = "8.x-1.x"
projects[leaflet][version] = "1.x-dev"

projects[libraries][type] = "module"
projects[libraries][download][type] = "git"
projects[libraries][download][branch] = "8.x-3.x"
projects[libraries][version] = "3.x-dev"

projects[link_class][type] = "module"
projects[link_class][version] = "1.3"

projects[lite][type] = "module"
projects[lite][version] = "1.4"

projects[mailsystem][type] = "module"
projects[mailsystem][version] = "4.1"

projects[media_entity][type] = "module"
projects[media_entity][version] = "1.6"
projects[media_entity][patch][] = "https://www.drupal.org/files/issues/remove-media-entity-version-constraints.patch"
projects[media_entity][patch][] = "https://www.drupal.org/files/issues/media-entity-revision-id-api-broken.patch"

projects[media_entity_document][type] = "module"
projects[media_entity_document][version] = "1.1"

projects[media_entity_image][type] = "module"
projects[media_entity_image][version] = "1.2"

projects[media_entity_instagram][type] = "module"
projects[media_entity_instagram][version] = "1.2"

projects[media_entity_twitter][type] = "module"
projects[media_entity_twitter][version] = "1.3"

projects[media_entity_video][type] = "module"
projects[media_entity_video][version] = "1.0-beta2"

projects[message][type] = "module"
projects[message][version] = "1.0-beta1"

projects[message_notify][type] = "module"
projects[message_notify][version] = "1.0-beta1"

projects[metatag][type] = "module"
projects[metatag][version] = "1.0"

projects[migrate_plus][type] = "module"
projects[migrate_plus][download][type] = "git"
projects[migrate_plus][download][branch] = "8.x-3.x"
projects[migrate_plus][version] = "3.x-dev"
projects[migrate_plus][patch][] = "https://www.drupal.org/files/issues/properly_integrate-2752335-33.patch"

projects[migrate_source_csv][type] = "module"
projects[migrate_source_csv][version] = "2.0"

projects[migrate_tools][type] = "module"
projects[migrate_tools][download][type] = "git"
projects[migrate_tools][download][branch] = "8.x-3.x"
projects[migrate_tools][version] = "3.x-dev"
projects[migrate_tools][patch][] = "https://www.drupal.org/files/issues/use_the_core_plugin-2795447-3.patch"

projects[moderation_note][type] = "module"
projects[moderation_note][download][type] = "git"
projects[moderation_note][download][branch] = "8.x-1.x"
projects[moderation_note][version] = "1.x-dev"

projects[moderation_sidebar][type] = "module"
projects[moderation_sidebar][download][type] = "git"
projects[moderation_sidebar][download][branch] = "8.x-1.x"
projects[moderation_sidebar][version] = "1.x-dev"

projects[multiversion][type] = "module"
projects[multiversion][version] = "1.0-alpha12"
projects[multiversion][patch][] = "https://www.drupal.org/files/issues/2822915-2.patch"
projects[multiversion][patch][] = "https://www.drupal.org/files/issues/2825477-2.patch"
projects[multiversion][patch][] = "https://www.drupal.org/files/issues/2844998-2.patch"

projects[oauth][type] = "module"
projects[oauth][download][type] = "git"
projects[oauth][download][branch] = "8.x-1.x"
projects[oauth][version] = "1.x-dev"

projects[page_manager][type] = "module"
projects[page_manager][version] = "4.0-beta1"

projects[panelizer][type] = "module"
projects[panelizer][version] = "4.0-beta3"

projects[panels][type] = "module"
projects[panels][version] = "4.0-beta1"
projects[panels][patch][] = "https://www.drupal.org/files/issues/panels-ipe-remove-activeclick.patch"
projects[panels][patch][] = "https://www.drupal.org/files/issues/panels_override_blocks-2767087-4.patch"
projects[panels][patch][] = "https://www.drupal.org/files/issues/panels-ipe-improve-flipper-logic.patch"

projects[pathauto][type] = "module"
projects[pathauto][version] = "1.0-rc1"

projects[replication][type] = "module"
projects[replication][version] = "1.0-alpha5"
projects[replication][patch][] = "https://www.drupal.org/files/issues/2814055-2.patch"
projects[replication][patch][] = "https://www.drupal.org/files/issues/2820105-10.patch"

projects[refreshless][type] = "module"
projects[refreshless][version] = "1.0-alpha3"

projects[rules][type] = "module"
projects[rules][version] = "3.0-alpha2"
projects[rules][patch][] = "https://www.drupal.org/files/issues/rules-Fix_type_definition_error-2694685-30.patch"
projects[rules][patch][] = "https://www.drupal.org/files/issues/rules-fix-tests.patch"
projects[rules][patch][] = "https://www.drupal.org/files/issues/rules-2824348-9.patch"
projects[rules][patch][] = "https://www.drupal.org/files/issues/rules-data-processor-type-checking.patch"

projects[replicate][type] = "module"
projects[replicate][download][type] = "git"
projects[replicate][download][branch] = "8.x-1.x"
projects[replicate][version] = "1.x-dev"

projects[replicate_ui][type] = "module"
projects[replicate_ui][download][type] = "git"
projects[replicate_ui][download][branch] = "8.x-1.x"
projects[replicate_ui][version] = "1.x-dev"

projects[responsive_preview][type] = "module"
projects[responsive_preview][download][type] = "git"
projects[responsive_preview][download][branch] = "8.x-1.x"
projects[responsive_preview][version] = "1.x-dev"

projects[restui][type] = "module"
projects[restui][download][type] = "git"
projects[restui][download][branch] = "8.x-1.x"
projects[restui][version] = "1.x-dev"

projects[revision_log_default][type] = "module"
projects[revision_log_default][download][type] = "git"
projects[revision_log_default][download][branch] = "8.x-1.x"
projects[revision_log_default][version] = "1.x-dev"

projects[scenarios][type] = "module"
projects[scenarios][download][type] = "git"
projects[scenarios][download][branch] = "8.x-2.x"
projects[scenarios][version] = "2.x-dev"

projects[scheduled_updates][type] = "module"
projects[scheduled_updates][version] = "1.0-alpha6"

projects[scheduler][type] = "module"
projects[scheduler][version] = "1.0-rc1"

projects[search_api][type] = "module"
projects[search_api][version] = "1.0-rc4"

projects[search_api_autocomplete][type] = "module"
projects[search_api_autocomplete][version] = "1.0-alpha1"

projects[search_api_solr][type] = "module"
projects[search_api_solr][version] = "1.0-beta2"

projects[search_autocomplete][type] = "module"
projects[search_autocomplete][download][type] = "git"
projects[search_autocomplete][download][branch] = "8.x-1.x"
projects[search_autocomplete][version] = "1.x-dev"

projects[swiftmailer][type] = "module"
projects[swiftmailer][download][type] = "git"
projects[swiftmailer][download][branch] = "8.x-1.x"
projects[swiftmailer][version] = "1.x-dev"

projects[token][type] = "module"
projects[token][version] = "1.0-rc1"

projects[typed_data][type] = "module"
projects[typed_data][version] = "1.0-alpha1"

projects[url_embed][type] = "module"
projects[url_embed][download][type] = "git"
projects[url_embed][download][branch] = "8.x-1.x"
projects[url_embed][version] = "1.x-dev"

projects[video_embed_field][type] = "module"
projects[video_embed_field][version] = "1.4"

projects[view_modes_display][version] = "1.0"
projects[view_modes_display][type] = "module"

projects[views_infinite_scroll][type] = "module"
projects[views_infinite_scroll][version] = "1.3"

projects[workbench_email][type] = "module"
projects[workbench_email][version] = "1.0-alpha4"

projects[workbench_moderation][type] = "module"
projects[workbench_moderation][version] = "1.2"
projects[workbench_moderation][patch][] = "https://www.drupal.org/files/issues/2685163-21.patch"
projects[workbench_moderation][patch][] = "https://www.drupal.org/files/issues/workbench_moderation-ability-to-rearrange-field-in-form-2833892-4.patch"
projects[workbench_moderation][patch][] = "https://www.drupal.org/files/issues/2668006-2.patch"
projects[workbench_moderation][patch][] = "https://www.drupal.org/files/issues/2847078-6.patch"
projects[workbench_moderation][patch][] = "https://www.drupal.org/files/issues/workbench_moderation-quickedit-support-2749503-6.patch"

projects[workspace][type] = "module"
projects[workspace][version] = "1.0-alpha4"

; Themes
projects[adminimal_theme][type] = "theme"
projects[adminimal_theme][version] = "1.3"

projects[zurb-foundation][type] = "theme"
projects[zurb-foundation][download][type] = "git"
projects[zurb-foundation][download][branch] = "8.x-6.x"
projects[zurb-foundation][version] = "6.x-dev"
projects[zurb-foundation][patch][] = "https://www.drupal.org/files/issues/accommodate-layout-changes-2871479-3.patch"

; Libraries
libraries[dropzone][destination] = "../../libraries"
libraries[dropzone][download][type] = "get"
libraries[dropzone][download][url] = "https://github.com/enyo/dropzone/archive/v4.2.0.zip"

libraries[fontawesome-iconpicker][destination] = "../../libraries"
libraries[fontawesome-iconpicker][download][type] = "get"
libraries[fontawesome-iconpicker][download][url] = "https://github.com/mjolnic/fontawesome-iconpicker/archive/1.2.0.zip"

libraries[masonry][destination] = "../../libraries"
libraries[masonry][download][type] = "get"
libraries[masonry][download][url] = "https://github.com/desandro/masonry/archive/v3.3.1.zip"

libraries[imagesloaded][destination] = "../../libraries"
libraries[imagesloaded][download][type] = "get"
libraries[imagesloaded][download][url] = "https://github.com/desandro/imagesloaded/archive/v3.1.8.zip"

libraries[ckeditor-track-changes][destination] = "../../libraries"
libraries[ckeditor-track-changes][download][type] = "get"
libraries[ckeditor-track-changes][download][url] = "https://github.com/loopindex/ckeditor-track-changes/archive/a7df5c6b685385713e8aaf2df3f44c82e3bf823a.zip"
libraries[ckeditor-track-changes][directory_name] = "lite"
; CKLite interferes with content insertion
; http://drupal.org/node/2482879
libraries[ckeditor-track-changes][patch][2482879] = "http://drupal.org/files/issues/cklite-content-insertion-2482879-4.patch"

libraries[slick][destination] = "../../libraries"
libraries[slick][download][type] = "get"
libraries[slick][download][url] = "https://github.com/kenwheeler/slick/archive/1.5.0.zip"

libraries[lightbox2][destination] = "../../libraries"
libraries[lightbox2][download][type] = "get"
libraries[lightbox2][download][url] = "https://github.com/lokesh/lightbox2/archive/v2.9.0.zip"

libraries[cropper][destination] = "../../libraries"
libraries[cropper][download][type] = "get"
libraries[cropper][download][url] = "https://github.com/fengyuanchen/cropper/archive/master.zip"
