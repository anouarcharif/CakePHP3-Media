<?php $sizes = getimagesize(WWW_ROOT.trim($media->file, '/'));  ?>
<div class="item <?php if($thumbID && $media->id === $thumbID): ?>thumbnail<?php endif; ?>">

		<input type="hidden" value="<?php echo $media->position; ?>" name="Media[<?= $media->ref_id;?>][<?php echo $media->id; ?>]">
		
		<div class="visu"><?php echo $this->Html->image($media->icon()); ?></div>
		<?php echo basename($media->file); ?>

		<div class="actions">
			<?php if($thumbID !== false && $media->id !== $thumbID && $media->type() == 'pic'): ?>
				<?php echo $this->Html->link(__d("media", "Mettre image à la une"),array('action'=>'thumb',$media->id)); ?> -
			<?php endif; ?>
			<?php echo $this->Html->link(__d('media',"Supprimer"),array('action'=>'delete',$media->id),array('class'=>'del')); ?>
			<?php if ($editor): ?>
				<?php if ($media->type() != 'pic'): ?>
					- <a href="" class="submit"><?php echo __d('media',"Insérer le lien l'article"); ?></a>
				<?php else: ?>
					- <a href="#" class="toggle"><?php echo __d('media',"Afficher"); ?></a>
				<?php endif ?>
			<?php endif ?>
		</div>
		<div class="expand">
			<table>
				<tr>
					<td style="width:140px"><?php echo $this->Html->image($media->file);?></td>
					<td>
						<p><strong><?php echo __d('media',"Nom du fichier"); ?> :</strong> <?php echo basename($media->file); ?></p>
						<p><strong><?php echo __d('media',"Taille de l'image"); ?> :</strong> <?php echo $sizes[0].'x'.$sizes[1]; ?></p>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td style="width:140px"><label><?php echo __d('media',"Titre"); ?></label></td>
					<td><input class="title" name="title" type="text" value="<?php echo basename($media->file); ?>"></td>
				</tr>
				<tr>
					<td style="width:140px"><label><?php echo __d('media',"Texte alternatif"); ?></label></td>
					<td><input class="alt" name="alt" type="text"></td>
				</tr>
				<tr>
					<td style="width:140px"><label><?php echo __d('media',"Cible du lien"); ?></label></td>
					<td><input class="href" name="href" type="text" value="<?php echo $media->file; ?>"></td>
				</tr>
				<tr>
					<td style="width:140px"><label><?php echo __d('media',"Alignement"); ?></label></td>
					<td>
						<input type="radio" name="align-<?php echo $media->id; ?>" class="align" id="align-none-<?php echo $media->id; ?>" value="none" checked>
						<?php echo $this->Html->image('/media/img/align-none.png'); ?><label for="align-none-<?php echo $media->id; ?>">Aucun</label>

						<input type="radio" name="align-<?php echo $media->id; ?>" class="align" id="align-left-<?php echo $media->id; ?>" value="left">
						<?php echo $this->Html->image('/media/img/align-left.png'); ?><label for="align-left-<?php echo $media->id; ?>">Gauche</label>

						<input type="radio" name="align-<?php echo $media->id; ?>" class="align" id="align-center-<?php echo $media->id; ?>" value="center">
						<?php echo $this->Html->image('/media/img/align-center.png'); ?><label for="align-center-<?php echo $media->id; ?>">Centre</label>

						<input type="radio" name="align-<?php echo $media->id; ?>" class="align" id="align-right-<?php echo $media->id; ?>" value="right">
						<?php echo $this->Html->image('/media/img/align-right.png'); ?><label for="align-right-<?php echo $media->id; ?>">Droite</label>
					</td>
				</tr>
				<tr>
					<td style="width:140px"><input type="hidden" class="filetype" name="filetype" value="<?php echo $media->type(); ?>" /></td>
					<td>
						<p><a href="" class="submit"><?php echo __d('media',"Insérer dans l'article"); ?></a> <?php echo $this->Html->link(__d('media',"Supprimer"),array('action'=>'delete',$media->id),array('class'=>'del')); ?></p>
					</td>
				</tr>
				<input type="hidden" name="file" value="<?php echo $media->file; ?>" class="file">
			</table>
		</div>
</div>