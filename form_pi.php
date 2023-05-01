<?php
header('Content-Type: text/html;  charset=UTF-8');
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/bdd.inc");


if (!isAuthUser())
{
    exit();
}
$right = getUserRight();
if (!isset($right['admin']) && !isset($right['SIWrite']))
{
    exit;
}


switch($_REQUEST["action"])
{
    case "piInsert":
        $w['site_id'] = $_GET["si_id"];

        $w['pi_lon'] =  $_GET["lon"];
        $w['pi_lat'] =  $_GET["lat"];
        $w['pi_id'] = "";
        $w['pi_type'] = "";
        $w['pi_description_courte'] = "";
        $w['pi_description_longue'] = "";
        $w['pi_complements'] = "";
        break;
    case "piUpdate":
        $w['site_id'] = $_GET["si_id"];

        $q = "select * from topo_pi where pi_id=".$_REQUEST['pi_id'];
        $ws = $Bdd->fetch_all_array($q);
        $w = $ws[0];
        break;
}
if (isset($_GET["lon"]) && isset($_GET["lat"]))
{
    $w['pi_lon'] =  $_GET["lon"];
    $w['pi_lat'] =  $_GET["lat"];
}




// on recherche les droit en lecture de site
$whereSite = "site_public = 1";
if (hasRight())
{
    if (isset($right['SIWrite']) && is_array($right['SIWrite']) )
    {
        $whereSite = "(topo_site.site_id in ('".implode("','",$right['SIWrite'])."'))";
    }

    if (isset($right['admin']))
    {
        $whereSite = "1 = 1";
    }
}

?>

<ul id="myTab" class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#generalesTab" role="tab" data-toggle="tab">Informations générales</a></li>
    <li><a href="#complementsTab" role="tab" data-toggle="tab">Informations complémentaires</a></li>
    <li><a href="#listSiteTab" role="tab" data-toggle="tab">Site(s) lié(s)</a></li>
</ul>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active in" id="generalesTab">

        <form class="form-horizontal" id="piSiteForm" role="form">
            <input name="si_id" value="<?=$w['site_id']?>" type="hidden">
            <input id="pi_id_link" name="pi_id" value="<?=$w['pi_id']?>" type="hidden">
            <input name="action" value="piSiteUpdate" type="hidden">
        </form>

        <form class="form-horizontal" id="piForm" role="form">
            <input id="pi_action" name="action" value="<?=$_REQUEST['action']?>" type="hidden">
            <input id="pi_id" name="pi_id" value="<?=$w['pi_id']?>" type="hidden">



            <div class="form-group">
                <label class="col-sm-3" class="sr-only" for="pi_lat">Latitude</label>
                <div class="col-sm-3">
                    <input name="w[pi_lat]" value="<?=$w['pi_lat']?>"class="form-control" id="voie_degaine">
                </div>
                <label class="col-sm-3" class="sr-only" for="pi_lon">Longitude</label>
                <div class="col-sm-3">
                    <input name="w[pi_lon]" value="<?=$w['pi_lon']?>"class="form-control" id="voie_hauteur">
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3" class="sr-only" for="pi_type">Type</label>
                <div class="col-sm-3">
                    <select id="pi_type" name="w[pi_type]" class="form-control">
                        <option />
                        <?php
                        foreach($config["PI"] as $k=>$v)
                        {
                            echo "<option value='".$k."' ".(($w['pi_type'] == $k)?"selected":"").">".$v["libelle"]."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3" class="sr-only" for="pi_description_courte">Description courte</label>
                <div class="col-sm-9">
                    <input name="w[pi_description_courte]" value="<?=$w['pi_description_courte']?>"class="form-control" id="pi_description_courte">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3" class="sr-only" for="pi_description_longue">Description longue</label>
                <div class="col-sm-9">
                    <textarea name="w[pi_description_longue]" class="form-control" id="pi_description_longue" ><?=$w['pi_description_longue']?></textarea>
                </div>
            </div>



            <textarea name="w[pi_complements]" class="form-control" id="pi_complements" style="display:none;"></textarea>
        </form>

    </div>
    <div class="tab-pane fade" id="complementsTab">

        <div class="form-group row">
            <label class="col-sm-3" >Ajouter un complements d'info</label>
            <div class="col-sm-9">
                <select class="form-control" id="AddCompFunc" name="add" onchange="rtg_AddCompFunc();"><option />
                    <?php
                    reset($config['complements']['pi']);
                    foreach($config['complements']['pi'] as $k=>$v)
                    {
                        echo "<option value=\"".$k."\">".$v['nom']."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>


        <form id="complementForm">

        </form>
    </div>
    <div class="tab-pane fade" id="listSiteTab">
        <div class="form-group">
            <label class="col-sm-3" class="sr-only" for="site_nom">Site(s) lié(s)</label>
            <div class="col-sm-9">

                <form id="listSiteForm">
                    <input id="piSiteAction" name="action" value="piUpdateSite" type="hidden">
                    <input name="pi_id" value="<?=$w['pi_id']?>" type="hidden">
                    <input name="delSi" id="delSi" value="" type="hidden">

                    <?php
                    // lecture des sites disponibles
                    $q = "select `topo_site`.`site_id` as id
			,`topo_site`.`site_nom` as name
			from topo_site
			where ".$whereSite;
                    $r = $Bdd->fetch_all_array($q);
                    for ($i=0;$i<sizeof($r);$i++)
                    {
                        $sitesBdd[$r[$i]['id']] = $r[$i]['name'];
                    }
                    function getSiteName($id)
                    {
                        return $GLOBALS['sitesBdd'][$id];
                    }
                    $q = "select * from topo_pi_site where pi_id=".$w['pi_id'];
                    $sites = $Bdd->fetch_all_array($q);
                    foreach ($sites as $n=>$site)
                    {
                        // on test si sur cette porté, j'ai des droits

                        if (in_array($site['site_id'],$right['SIWrite']) || isAdmin())
                        {
                            $c++;
                            echo '<div class="row row'.($c%2).'">';
                            echo '<div class="col-sm-7">'.getSiteName($site['site_id']).'</div>';
                            echo '<div class="col-sm-5"><a class="btn btn-default glyphicon glyphicon-trash btn-danger" onclick="return delPiSite(\''.$site['site_id'].'\');" aria-hidden="true"></a></div>';
                            echo '</div>';
                        }

                    }
                    echo '<div class="row row'.($c%2).'">';
                    echo '<div class="col-sm-7"><select name="addSi" id="addSi" class="form-control">';
                    reset($sitesBdd);
                    foreach ($sitesBdd as $k=>$v)
                    {
                        echo "<option value='".$k."'>".$v."</option>";
                    }
                    echo '</select></div>';
                    echo '<div class="col-sm-5"><a class="btn btn-default glyphicon glyphicon-plus btn-primary" onclick="return addPiSite();" aria-hidden="true"></a></div>';
                    echo '</div></div>';
                    ?>
                </form>
            </div>
        </div>


    </div>
    <script type="text/javascript">
        function pi_form_sav()
        {
            $('#pi_complements').val(JSON.stringify($('#complementForm').serializeArray()));
            var id = rtg_setDatas($('#piForm') ,'<?=$w['pi_id']?>');
            console.log(id)
            if (id > 0)
            {
                $('#pi_id').val(id);
                $('#pi_id_link').val(id);
                rtg_setDatas($('#piSiteForm') ,id);
            }
            $('#pi_action').val("piUpdate");
            return false
        }
        function pi_form_del()
        {
            if (confirm("Etes vous sur ?")) {
                $('#pi_action').val("piDel");
                rtg_setDatas($('#piForm') ,'<?=$w['pi_id']?>');
                $('#pi_action').val("piInsert");
                return false;
            }
            return false;
        }

        function delPiSite(delSi)
        {
            $('#delSi').val(delSi);
            $('#piSiteAction').val("piDelSite");
            var id = rtg_setDatas($('#listSiteForm') ,'<?=$w['pi_id']?>');
            $('#event-modal').modal('hide')
        }
        function addPiSite()
        {
            $('#piSiteAction').val("piAddSite");
            var id = rtg_setDatas($('#listSiteForm') ,'<?=$w['pi_id']?>');
            $('#event-modal').modal('hide')
        }


        rtg_RegenForm(<?=($w['pi_complements'])?$w['pi_complements']:"{}" ?>);
    </script>
    <div id="popupBody" class="modal-footer">
        <div class="col-md-12">
            <div class="col-md-7" id="savPrgBar"> </div>
            <div class="col-md-5">
                <a class="btn btn-primary glyphicon glyphicon-floppy-save" onclick="return pi_form_sav();"> Sauvegarder</a>
                <a class="btn btn-danger glyphicon glyphicon-trash" onclick="return pi_form_del();"> Supprimer</a>
                <a class="btn btn-default glyphicon glyphicon-remove" data-dismiss="modal" aria-hidden="true"> Fermer</a>
            </div>
        </div>




