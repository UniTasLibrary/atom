<oai_dc:dc
    xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai_dc/
    http://www.openarchives.org/OAI/2.0/oai_dc.xsd">

  <?php if (!empty($resource->title)): ?>
    <dc:title><?php echo esc_specialchars(render_title($resource->getCollectionRoot())) ?> / <?php echo esc_specialchars(strval($resource->title)) ?></dc:title>
  <?php endif; ?>

  <?php foreach ($resource->getCreators() as $item): ?>
    <dc:creator><?php echo esc_specialchars(strval($item)) ?></dc:creator>
  <?php endforeach; ?>

  <?php foreach ($dc->subject as $item): ?>
    <dc:subject><?php echo esc_specialchars(strval($item)) ?></dc:subject>
  <?php endforeach; ?>

  <?php if (!empty($resource->scopeAndContent)): ?>
    <dc:description><?php echo esc_specialchars(strval($resource->scopeAndContent)) ?></dc:description>
  <?php endif; ?>

  <?php foreach ($resource->getPublishers() as $item): ?>
    <dc:publisher><?php echo esc_specialchars(strval($item)) ?></dc:publisher>
  <?php endforeach; ?>

  <?php foreach ($resource->getContributors() as $item): ?>
    <dc:contributor><?php echo esc_specialchars(strval($item)) ?></dc:contributor>
  <?php endforeach; ?>

  <?php foreach ($dc->date as $item): ?>
    <dc:date><?php echo esc_specialchars(strval($item)) ?></dc:date>
  <?php endforeach; ?>

  <?php foreach ($dc->type as $item): ?>
    <dc:type><?php echo esc_specialchars(strval($item)) ?></dc:type>
  <?php endforeach; ?>

  <?php foreach ($dc->format as $item): ?>
    <dc:format><?php echo esc_specialchars(strval($item)) ?></dc:format>
  <?php endforeach; ?>

  <?php if (count($resource->digitalObjects)): ?>
    <?php foreach ($resource->digitalObjects as $digitalObject): ?>
      <?php if ($digitalObject->usageId == QubitTerm::OFFLINE_ID): ?>
        <dc:identifier linktype="notonline"><?php echo esc_specialchars($resource['referenceCode']) ?></dc:identifier>
        <dc:identifier linktype="notonline"><?php echo esc_specialchars(sfConfig::get('app_siteBaseUrl') .'/'.$resource->slug) ?></dc:identifier>
      <?php endif; ?>
      <?php if ($digitalObject->usageId == QubitTerm::MASTER_ID && QubitAcl::check($resource, 'read')): ?>
        <?php $digitalObjectUrl = (string)QubitSetting::getByName('siteBaseUrl') . $digitalObject->path . $digitalObject->name ?>
        <dc:identifier linktype="fulltext"><?php echo esc_specialchars($resource['referenceCode']) ?></dc:identifier>
        <dc:identifier linktype="fulltext"><?php echo esc_specialchars(sfConfig::get('app_siteBaseUrl') .'/'.$resource->slug) ?></dc:identifier>
        <viewcopy linktype="fulltext"><?php echo esc_specialchars($digitalObjectUrl) ?></viewcopy>
      <?php elseif ($digitalObject->usageId == QubitTerm::EXTERNAL_URI_ID): ?>
        <dc:identifier linktype="restricted"><?php echo esc_specialchars($resource['referenceCode']) ?></dc:identifier>
        <dc:identifier linktype="restricted"><?php echo esc_specialchars(sfConfig::get('app_siteBaseUrl') .'/'.$resource->slug) ?></dc:identifier>
        <viewcopy linktype="restricted"><?php echo esc_specialchars($digitalObjectUrl) ?></viewcopy>
      <?php endif; ?>
      <?php $thumbnail = $digitalObject->getChildByUsageId(QubitTerm::THUMBNAIL_ID) ?>
      <?php $thumbnailUrl = (string)QubitSetting::getByName('siteBaseUrl') . $thumbnail->path . $thumbnail->name ?>
      <dc:identifier linktype="thumbnail"><?php echo esc_specialchars($thumbnailUrl) ?></dc:identifier>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if (!empty($resource->locationOfOriginals)): ?>
    <dc:source><?php echo esc_specialchars(strval($resource->locationOfOriginals)) ?></dc:source>
  <?php endif; ?>

  <?php foreach ($resource->language as $code): ?>
    <dc:language xsi:type="dcterms:ISO639-3"><?php echo esc_specialchars(strval(strtolower($iso639convertor->getID3($code)))) ?></dc:language>
  <?php endforeach; ?>

  <?php if (isset($resource->repository)): ?>
    <dc:relation><?php echo esc_specialchars(sfConfig::get('app_siteBaseUrl').'/'.$resource->repository->slug) ?></dc:relation>
    <dc:relation><?php echo esc_specialchars(strval($resource->repository->authorizedFormOfName)) ?></dc:relation>
  <?php endif; ?>

  <?php foreach ($dc->coverage as $item): ?>
    <dc:coverage><?php echo esc_specialchars(strval($item)) ?></dc:coverage>
  <?php endforeach; ?>

  <?php if (!empty($resource->accessConditions)): ?>
    <dc:rights><?php echo esc_specialchars(strval($resource->accessConditions)) ?></dc:rights>
  <?php endif; ?>

</oai_dc:dc>
