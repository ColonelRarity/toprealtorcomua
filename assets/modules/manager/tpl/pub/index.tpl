<? if ($flash['success'] != ''): ?>
    <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?=$flash['success']?>
    </div>
<? endif ?>
<form action="index.php" method="get" id="search_form" style="display: block;">
<?php foreach ($_GET as $key => $value) : ?>
    <input type="hidden" name="<?=$key?>" value="<?=$value?>">        
<?php endforeach ?>
    <h3><?=M_SEARCH?></h3>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-4">
                <h4><?=M_TITLE?></h4>
                <input type="text" name="title" placeholder="<?=M_PUB_ENTER_NAME?>" class="form-control" <?=(isset($_GET['title']) ? 'value="'.$_GET['title'].'"' : '')?>>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <h4><?=M_PRACTICE?></h4>
                <select class="form-control" name="practik" data-live-search="true">
                    <option value=""> - </option>
                    <? while ($practik = $modx->db->getRow($practiks)) : ?>
                        <option value="<?=$practik['id']?>" <?=($_GET['practik'] == $practik['id'] ? "selected" : "")?>><?=$practik['pagetitle']?></option>
                    <? endwhile; ?>
                    
                </select>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <h4><?=M_TYPE?></h4>
                <select class="form-control" name="type" data-live-search="true">
                    <option value=""> - </option>
                    <option value="0" <?=($_GET['type'] == "0" ? "selected" : "")?>><?=M_PUB_NEWS?></option>
                    <option value="1" <?=($_GET['type'] == 1 ? "selected" : "")?>><?=M_PUB_PROJECT?></option>
                    <option value="2" <?=($_GET['type'] == 2 ? "selected" : "")?>><?=M_PUB_PUBLIC?></option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <p>
                <br>
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> <b><?=M_SEARCH?></b></button>
            </p>
        </div>
    </div>
</form>
<p><a href="<?=$url?>b=pub&c=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> <?=M_PUB_ADD?></a></p>

<table class="table table-condensed table-bordered table-striped">
<thead>
<tr>
    <th width="35px">ID</th>
    <th width="150px"><?=M_DATE?></th>
    <th width="150px"><?=M_PRACTICE?></th>
    <th width="150px"><?=M_PRACTICE?></th>
    <th width=""><?=M_TITLE?></th>
    <th width="200px"><?=M_USER?></th>
    <th width="135px"></th>
</tr>
</thead>
<tbody>
<?php while ($pub = $modx->db->getRow($pubs)) : ?>
<tr class="<?=($pub['status'] == 1 ? "success" : "danger")?>">
    <td><?=$pub['id']?></td>
    <td><?=$pub['date']?></td>
    <td><?=$pub['practika_name']?></td>
    <td>
        <?
            switch ($pub['type']) {
                case 0: echo M_PUB_NEWS; break;
                case 1: echo M_PUB_PROJECT; break;
                case 2: echo M_PUB_PUBLIC; break;
            }
        ?>
    </td>
    <td><?=$pub['title']?></td>
    <td><?=$pub['user_name']?></td>
    <td>
        <a href="<?=$url?>b=pub&c=edit&i=<?=$pub['id']?>" class="btn btn-sm btn-primary" title="<?=M_EDIT?>"><span class="glyphicon glyphicon-pencil"></span></a>
        <? if($pub['status'] == 1) { ?>
            <a href="<?=$url?>b=pub&c=hide_pub&i=<?=$pub['id']?>" class="btn btn-sm btn-danger" title="<?=M_PUB_UNBAN?>"><span class="glyphicon glyphicon-eye-close"></span></a>
        <? } else { ?>
            <a href="<?=$url?>b=pub&c=show_pub&i=<?=$pub['id']?>" class="btn btn-sm btn-success" title="<?=M_PUB_BAN?>"><span class="glyphicon glyphicon-eye-open"></span></a>
        <? } ?>
        <a href="<?=$modx->makeUrl(38,'','','','en').$pub['url']?>/" class="btn btn-sm btn-info" title="<?=M_VIEW?>" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a>
    </td>   
</tr>
<? endwhile;?>
</tbody>
</table>
<nav>
    <ul class="pagination">
        <?=$pagin?>
    </ul>
</nav>          