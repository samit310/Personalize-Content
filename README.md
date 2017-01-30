# Personalize-Content [Drupal 8]

Purpose: Personalize site content based on user IP Address.

Requirement:

 
Geoip [https://www.drupal.org/project/geoip]

Address [https://www.drupal.org/project/address]

How to configure:

1: Add a Country field [List(text)] to content type with following country code values

   JP|Japan
   IN|India

2: Figure out all Views where the Personalize content feature is required.

3: Add Above country field to the all required Views in FILTER CRITERIA section.

4: Choose a default country used for hook_views_query_alter. [Please check hook_views_query_alter inside personalization_algo.module file]

5: Inside hook_views_query_alter we get the user’s Country iso code and change the key of country field.

6: Add GeoLite2-City.mmdb file inside sites/default/files Directory. 

Concerns: We have to disable Drupal page cache programmatically to Personalize site content based on user IP Address.
[Check personalization_algo/src/EventSubscriber/PageViewEvent.php file]

Future enhancement: We can add Logged-in user condition inside PageViewEvent.php file, Because the issue of Drupal page cache occur with Anonymous User. 
