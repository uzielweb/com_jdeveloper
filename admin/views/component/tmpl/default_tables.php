<?php
/**
 * @package     JDeveloper
 * @subpackage  Views
 *
 * @copyright  	Copyright (C) 2014, Tilo-Lars Flasche. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$input = JFactory::getApplication()->input;

$tables = $this->tables;
$model_table = JModelLegacy::getInstance("Table", "JDeveloperModel");
$model_fields = JModelLegacy::getInstance("Fields", "JDeveloperModel");
$model_field = JModelLegacy::getInstance("Field", "JDeveloperModel");

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<h2><?php echo JText::_("COM_JDEVELOPER_COMPONENT_TABLES"); ?></h2>
<ul class="nav nav-pills nav-justified">
	<?php foreach ($tables as $i => $table) : ?>
		<li <?php if (isset($this->active[1]) && $table->id == $this->active[1]) : ?>class="active"<?php endif; ?>><a href="#table<?php echo $table->id; ?>" data-toggle="pill"><?php echo $table->name; ?></a></li>
	<?php endforeach; ?>
	</ul>
	<div class="tab-content">
		<?php foreach ($tables as $i => $table) : ?>
			<?php 
				$tableIsInstalled = $model_table->isInstalled($table->id);
			?>
			<div class="tab-pane <?php if (isset($this->active[1]) && $table->id == $this->active[1]) : ?>active<?php endif; ?>" id="table<?php echo $table->id; ?>">
				<i style="color:#999999;font-size:20px;font-weight:700;">#__<?php echo $table->dbname; ?> </i>
				  <a role="menuitem" tabindex="-1" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=field.add&component=" . $this->item->id . "&table=" . $table->id, false); ?>" class="btn btn-success">
					<i class="icon-new"></i> <?php echo JText::_("JTOOLBAR_ADD_FIELD"); ?>
				  </a>
				  <a role="menuitem" tabindex="-1" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.edit&id=" . $table->id . "&component=" . $this->item->id); ?>" class="btn">
					<i class="icon-edit"></i> <?php echo JText::_("JTOOLBAR_EDIT"); ?>
				  </a>
				  <a role="menuitem" tabindex="-1" onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x*0.8, y: window.getSize().y*0.8}, url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=fields&tmpl=component&list[fullordering]=a.ordering%20asc&filter[table]=" . $table->id) ?>'})" class="btn">
					<i class="icon-list"></i> <?php echo JText::_("COM_JDEVELOPER_TABLE_ORDER_FIELDS") ?>
				  </a>
				  <?php if (!$model_table->isInstalled($table->id)) : ?>
				  <a role="menuitem" tabindex="-1" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.install&id=" . $table->id, false); ?>" class="btn btn-success">
					<i class="icon-upload"></i> <?php echo JText::_("JTOOLBAR_INSTALL"); ?>
				  </a>
				  <?php else : ?>
				  <a role="menuitem" tabindex="-1" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.uninstall&id=" . $table->id, false); ?>" class="btn btn-warning">
					<i class="icon-unpublish"></i> <?php echo JText::_("JTOOLBAR_UNINSTALL"); ?>
				  </a>			  
				  <?php endif; ?>
				  <a role="menuitem" tabindex="-1" data-toggle="modal" data-target="#deleteTable<?php echo $table->id; ?>" class="btn btn-danger">
					<i class="icon-delete"></i> <?php echo JText::_("JTOOLBAR_DELETE"); ?>
				  </a>
				  <a role="menuitem" tabindex="-1" onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8}, url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=table&layout=form&tmpl=component&id=" . $table->id) ?>'})" class="btn btn-info">
					Form
				  </a>
				  <a role="menuitem" tabindex="-1" onclick="SqueezeBox.fromElement(this, {handler:'iframe', size: {x: window.getSize().x * 0.6, y: window.getSize().y * 0.8}, url:'<?php echo JRoute::_("index.php?option=com_jdeveloper&view=table&layout=sql&tmpl=component&id=" . $table->id) ?>'})" class="btn btn-info">
					SQL
				  </a>
				<p>&nbsp;</p>
				<div class="row-fluid">
					<div class="span6">
						<h2><?php echo JText::_("COM_JDEVELOPER_COMPONENT_INFO") ?></h2>
						<table class="table">
							<tbody>
								<tr>
									<td><?php echo JText::_("COM_JDEVELOPER_TABLE_FIELD_PLURAL_LABEL") ?>:</td>
									<td><?php echo $table->plural; ?></td>
								</tr>
								<tr>
									<td><?php echo JText::_("COM_JDEVELOPER_TABLE_FIELD_SINGULAR_LABEL") ?>:</td>
									<td><?php echo $table->singular; ?></td>
								</tr>
								<tr>
									<td><?php echo JText::_("COM_JDEVELOPER_TABLE_FIELD_FRONTEND_LABEL") ?>:</td>
									<td><span class="badge badge-<?php echo $table->params["frontend"] ? "success" : "important"; ?>"><?php echo $table->params["frontend"] ? JText::_("JYES") : JText::_("JNO"); ?></span></td>
								</tr>
								<tr>
									<td><?php echo JText::_("COM_JDEVELOPER_TABLE_FIELD_CATEGORIES_LABEL") ?>:</td>
									<td><span class="badge badge-<?php echo $table->jfields["catid"] ? "success" : "important"; ?>"><?php echo $table->jfields["catid"] ? JText::_("JYES") : JText::_("JNO"); ?></span></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="span6">
						<h2><?php echo JText::_("COM_JDEVELOPER_TABLE_FIELDSET_JOOMLA_DB_PATTERN") ?></h2>
						<table class="table">
							<tbody>
							<tr>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=catid", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-category-<?php echo $table->jfields["catid"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=published", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-published-<?php echo $table->jfields["published"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=created_by", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-created-by-<?php echo $table->jfields["created_by"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=modified_by", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-modified-by-<?php echo $table->jfields["modified_by"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=created", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-created-<?php echo $table->jfields["created"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=modified", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-modified-<?php echo $table->jfields["modified"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=publish_up", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-publish-up-<?php echo $table->jfields["publish_up"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=publish_down", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-publish-down-<?php echo $table->jfields["publish_down"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=version", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-version-<?php echo $table->jfields["version"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
							</tr>
							<tr>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=ordering", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-ordering-<?php echo $table->jfields["ordering"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=alias", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-alias-<?php echo $table->jfields["alias"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=hits", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-hits-<?php echo $table->jfields["hits"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=checked_out", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-checked-out-<?php echo $table->jfields["checked_out"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=access", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-access-<?php echo $table->jfields["access"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=asset_id", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-asset-id-<?php echo $table->jfields["asset_id"] ? "" : "in"; ?>active.png" />
									</a>
								</td>	
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=language", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-language-<?php echo $table->jfields["language"] ? "" : "in"; ?>active.png" />
									</a>
								</td>	
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=params", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-params-<?php echo $table->jfields["params"] ? "" : "in"; ?>active.png" />
									</a>
								</td>
								<td>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.togglejfield&id=" . $table->id . "&jfield=metadata", false); ?>">
										<img height="72" width="72" src="<?php echo JURI::root(true);?>/media/com_jdeveloper/images/icon-metadata-<?php echo $table->jfields["metadata"] ? "" : "in"; ?>active.png" />
									</a>
								</td>							
							</tr>
							</tbody>
						</table>					
					</div>
				</div>
				<table class="table table-striped table-bordered" id="fieldList<?php echo $table->id ?>">
					<thead>
						<tr>
							<th class="nowrap left">
								<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_NAME_LABEL', 'a.name', $listDirn, $listOrder) ?>
							</th>
							<th class="nowrap left">
								<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_TYPE_LABEL', 'a.type', $listDirn, $listOrder) ?>
							</th>
							<th class="nowrap left">
								<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_RULE_LABEL', 'a.rule', $listDirn, $listOrder) ?>
							</th>
							<th class="nowrap left hidden-phone">
								<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_LABEL_LABEL', 'a.label', $listDirn, $listOrder) ?>
							</th>
							<th class="nowrap left hidden-phone">
								<a><?php echo JText::_('COM_JDEVELOPER_FIELD_FIELD_DESCRIPTION_LABEL') ?></a>
							</th>
							<th class="nowrap left hidden-phone">
								<a><?php echo JText::_('COM_JDEVELOPER_FIELD_DATABASE') ?></a>
							</th>
							<th class="nowrap left">
								<?php echo JHtml::_('searchtools.sort', 'COM_JDEVELOPER_FIELD_FIELD_ID_LABEL','a.id', $listDirn, $listOrder) ?>
							</th>
							<th class="nowrap left"><?php echo JText::_('COM_JDEVELOPER_COMPONENT_FIELD_EDIT_LABEL'); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $this->escape($table->pk); ?></td>						
							<td><i style="color:grey; font-size:12px;">INT(11)</i></td>						
							<td></td>						
							<td><?php echo $this->escape($table->pk); ?></td>						
							<td><?php echo $this->escape($table->pk); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php if ($table->jfields['asset_id']) : ?>
						<tr>
							<td>asset_id</td>						
							<td><i style="color:grey; font-size:12px;">INT(11)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_ACTION_PERMISSIONS_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_ASSET_ID_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['catid']) : ?>
						<tr>
							<td>catid</td>						
							<td><i style="color:grey; font-size:12px;">INT(11)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JCATEGORY"); ?></td>
							<td><?php echo JText::_("JCATEGORY"); ?></td>
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if (isset($table->params["relations"])) foreach ($table->params["relations"] as $relation) : ?>					
					<?php $relation = $model_table->getItem($relation); ?>
						<tr>
							<td><?php echo $relation->name; ?></td>						
							<td><i style="color:grey; font-size:12px;">INT(11)</i></td>						
							<td></td>						
							<td><?php echo ucfirst($relation->name); ?></td>						
							<td>Link to the primary key of an item of the <b><?php echo $relation->dbname; ?></b> table.</td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endforeach; ?>
					<?php foreach ($model_fields->getTableFields($table->id) as $i => $field) : ?>
						<tr>
							<td>
								<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=field.edit&id=" . $field->id . "&component=" . $this->item->id); ?>"><?php echo $this->escape($field->name); ?></a>
							</td>
							<td>
								<?php if (!empty($field->formfield_id)) : ?>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=formfield.edit&id=" . $this->escape($field->formfield_id)) ?>">
										<?php echo ucfirst($this->escape($field->type)); ?>
									</a>
								<?php else : ?>
									<?php echo ucfirst($this->escape($field->type)); ?>
								<?php endif; ?>
								<br><i style="color:grey; font-size:12px;"><?php echo $this->escape($field->dbtype) . '(' . $field->maxlength . ')'; ?></i>
							</td>
							<td>
								<?php if (!empty($field->formrule_id)) : ?>
									<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=formrule.edit&id=" . $this->escape($field->formrule_id)) ?>">
										<?php echo ucfirst($this->escape($field->rule)); ?>
									</a>
								<?php else : ?>
									<?php echo ucfirst($this->escape($field->rule)); ?>
								<?php endif; ?>
							</td>
							<td><?php echo $this->escape($field->label); ?></td>
							<td>
								<?php
								if (strlen($this->escape($field->description)) > 50)
									echo substr($this->escape($field->description), 0, 50) . " ...";
								else
									echo $this->escape($field->description);
								?>
							</td>
							<td>
							<?php if ($tableIsInstalled) : ?>
								<?php if (!$field->isInstalled) : ?>
									<a role="menuitem" tabindex="-1" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=field.install&id=" . $field->id, false); ?>" class="btn btn-warning">
										<i class="icon-upload"></i> <?php echo JText::_("JTOOLBAR_EXPORT"); ?>
									</a>
								<?php else : ?>
									<i class="icon-save"></i>						
								<?php endif; ?>
							<?php endif; ?>
							</td>
							<td><?php echo $this->escape($field->id); ?></td>
							<td class="list-edit">
								<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=field.edit&id=" . $field->id . "&component=" . $this->item->id); ?>"><i class="icon-edit"></i></a>
								<a href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=field.delete&id=" . $field->id); ?>" style="color:#ff0000;"><i class="icon-delete"></i></a>
							</td>
						</tr>
					<?php endforeach; ?>
					<!-- Fields from database which don't exist in JDeveloper table -->
					<?php if ($tableIsInstalled) : ?>
						<?php foreach ($model_table->compareToDatabaseTable($table->id, $table->dbname) as $column) : ?>
							<?php if (!$model_field->isCoreField($column->Field) && $column->Field != $table->pk) : ?>
						<tr>
							<td style="color:#999999"><?php echo $column->Field; ?></td>
							<td><?php echo $column->Type; ?></td>						
							<td></td>												
							<td></td>												
							<td></td>												
							<td>
								<a role="menuitem" tabindex="-1" href="<?php echo JRoute::_("index.php?option=com_jdeveloper&task=table.importFieldFromDatabase&id=" . $table->id . "&column=" . $column->Field, false); ?>" class="btn btn-info">
									<i class="icon-download"></i> <?php echo JText::_("JTOOLBAR_IMPORT"); ?>
								</a>
							</td>												
							<td></td>												
							<td></td>
						</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif;?>
					<?php if ($table->jfields['alias']) : ?>
						<tr>
							<td>alias</td>						
							<td><i style="color:grey; font-size:12px;">varchar(255)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JFIELD_ALIAS_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_ALIAS_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['ordering']) : ?>
						<tr>
							<td>ordering</td>						
							<td><i style="color:grey; font-size:12px;">int(11)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JFIELD_ORDERING_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_ORDERING_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['published']) : ?>
						<tr>
							<td>published</td>						
							<td><i style="color:grey; font-size:12px;">tinyint(3)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JSTATUS"); ?></td>						
							<td><?php echo JText::_("JFIELD_PUBLISHED_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['checked_out']) : ?>
						<tr>
							<td>checked_out</td>						
							<td><i style="color:grey; font-size:12px;">int(11)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JFIELD_CHECKED_OUT_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_CHECKED_OUT_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
						<tr>
							<td>checked_out_time</td>						
							<td><i style="color:grey; font-size:12px;">datetime</i></td>						
							<td></td>						
							<td><?php echo JText::_("JFIELD_CHECKED_OUT_TIME_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_CHECKED_OUT_TIME_DESC"); ?></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['created']) : ?>
						<tr>
							<td>created</td>						
							<td><i style="color:grey; font-size:12px;">datetime</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_CREATED_LABEL"); ?></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_CREATED_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['created_by']) : ?>
						<tr>
							<td>created_by</td>						
							<td><i style="color:grey; font-size:12px;">int(11)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_CREATED_BY_LABEL"); ?></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_CREATED_BY_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['created_by_alias']) : ?>
						<tr>
							<td>created_by_alias</td>						
							<td><i style="color:grey; font-size:12px;">varchar(255)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_CREATED_BY_ALIAS_LABEL"); ?></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_CREATED_BY_ALIAS_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['modified']) : ?>
						<tr>
							<td>modified</td>						
							<td><i style="color:grey; font-size:12px;">datetime</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_MODIFIED_LABEL"); ?></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_MODIFIED_LABEL"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['modified_by']) : ?>
						<tr>
							<td>modified_by</td>						
							<td><i style="color:grey; font-size:12px;">int(11)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_MODIFIED_BY_LABEL"); ?></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_MODIFIED_BY_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['publish_up']) : ?>
						<tr>
							<td>publish_up</td>						
							<td><i style="color:grey; font-size:12px;">datetime</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_PUBLISH_UP_LABEL"); ?></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_PUBLISH_UP_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['publish_down']) : ?>
						<tr>
							<td>publish_down</td>						
							<td><i style="color:grey; font-size:12px;">datetime</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_PUBLISH_DOWN_LABEL"); ?></td>						
							<td><?php echo JText::_("JGLOBAL_FIELD_PUBLISH_DOWN_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['images']) : ?>
						<tr>
							<td>images</td>						
							<td><i style="color:grey; font-size:12px;">text</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_FIELDSET_IMAGE_OPTIONS"); ?></td>						
							<td><?php echo JText::_("JGLOBAL_FIELDSET_IMAGE_OPTIONS"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['version']) : ?>
						<tr>
							<td>version</td>						
							<td><i style="color:grey; font-size:12px;">int(11)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JFIELD_VERSION_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_VERSION_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['hits']) : ?>
						<tr>
							<td>hits</td>						
							<td><i style="color:grey; font-size:12px;">int(11)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_HITS"); ?></td>						
							<td><?php echo JText::_("JGLOBAL_HITS"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['access']) : ?>
						<tr>
							<td>access</td>						
							<td><i style="color:grey; font-size:12px;">int(11)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JFIELD_ACCESS_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_ACCESS_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['language']) : ?>
						<tr>
							<td>language</td>						
							<td><i style="color:grey; font-size:12px;">char(7)</i></td>						
							<td></td>						
							<td><?php echo JText::_("JFIELD_LANGUAGE_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_LANGUAGE_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['params']) : ?>
						<tr>
							<td>params</td>						
							<td><i style="color:grey; font-size:12px;">text</i></td>						
							<td></td>						
							<td><?php echo JText::_("JFIELD_PARAMS_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_PARAMS_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['metadata']) : ?>
						<tr>
							<td>metadata</td>						
							<td><i style="color:grey; font-size:12px;">text</i></td>						
							<td></td>						
							<td><?php echo JText::_("JGLOBAL_FIELDSET_METADATA_OPTIONS"); ?></td>						
							<td><?php echo JText::_("JGLOBAL_FIELDSET_METADATA_OPTIONS"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['metakey']) : ?>
						<tr>
							<td>metakey</td>						
							<td><i style="color:grey; font-size:12px;">text</i></td>						
							<td></td>						
							<td><?php echo JText::_("JFIELD_META_KEYWORDS_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_META_KEYWORDS_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					<?php if ($table->jfields['metadesc']) : ?>
						<tr>
							<td>metadesc</td>						
							<td><i style="color:grey; font-size:12px;">text</i></td>						
							<td></td>						
							<td><?php echo JText::_("JFIELD_META_DESCRIPTION_LABEL"); ?></td>						
							<td><?php echo JText::_("JFIELD_META_DESCRIPTION_DESC"); ?></td>						
							<td></td>						
							<td></td>						
							<td></td>						
						</tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		<?php endforeach; ?>
		</div>