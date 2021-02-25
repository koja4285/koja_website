<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @author    Kohei Koja
 */

echo $this->Html->css('posts', ['block' => 'css']);
?>

<h1 id="post-title"><?= h($post->title) ?></h1>
<p id="post-created"><small>Created: <?= $post->created->format(DATE_RFC850) ?></small></p>
<div id="post-body">
    <p><?= $this->Text->autoParagraph($post->body) ?></p>
</div>

<hr id="border"></hr>

<h3>Comments</h3>
<?php if (!isset($comments)): ?>
    <p>No comments so far... Please dump any comment (-_-)</p>
<?php else: ?>
    <?php // To avoid naming confilication, use $c instead of $comment ?>
    <?php foreach($comments as $c): ?>
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">
                <span><img src="/img/comments/user_icon" alt="" height="35px"></span>
                <?php if (isset($c->user_id)): ?>
                    <?= $c->user->username ?>
                <?php else: ?>
                    <?= $c->guestname ?>
                <?php endif; ?>
                <span class="fs-5" id="comment-created"><?= $c->created->format('Y/m/d-H:i:s') ?></span>
            </div>
            <div class="card-body">
                <p class="card-text"><?= $c->content ?></p>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="content mx-3">
    <?= $this->Form->create($comment) ?>
    <?= '//TODO add reply feature' ?>
    <fieldset>
        <?php
            if (isset($thisUser))
            {
                echo $this->Form->control('username', [
                    'disabled' => true,
                    'class' => 'form-control fs-4',
                    'value' => $thisUser['username']
                ]);
            }
            else
            {
                echo $this->Form->control('guestname', [
                    'id' => 'comment-form'
                ]);
            }
            echo $this->Form->control('content', [
                'label' => 'Comment',
                'value' => '',
                'placeholder' => 'Type your thought!'
            ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('¡Comment!'), [ 'id' => 'comment-submit' ]) ?>
    <?= $this->Form->end() ?>
</div>
