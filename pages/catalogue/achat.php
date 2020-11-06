<!DOCTYPE html>
<html lang="fr">
<?php
include 'include/head.php';
?>

<body class="wide">
    <!-- WRAPPER -->
    <div class="wrapper">
        <?php include 'include/topbar.php'; ?>
        <?php include 'include/header.php'; ?>
        <?php include 'include/tb_popup.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@13.0.1/dist/lazyload.min.js"></script>
        <script src="js/language.js"></script>

        <style>
            * {
                box-sizing: border-box;
            }

            body {
                font-family: sans-serif;
            }

            /* ---- grid ---- */

            .grid-item--width3 {
                width: 200px;
            }

            .grid-item--height3 {
                height: 300px;
            }
        </style>

        <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-3 searchCol">
                        <div class="row">
                            <div class="col-md-12 background-green" id="achat_sidebar">
                                <h1 class="text-light"><?= L::achat_searchbar_title; ?></h1>

                                <div class="form-group">
                                    <label for="widget-contact-form-marque"><?= L::achat_searchbar_brand; ?></label>
                                    <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })" class="portfolio" data-filter-group="brand" name="widget-contact-form-marque" id="widget-bike-brand">
                                        <option data-filter="" value="*"><?= L::achat_brand_option1; ?></option>
                                        <option data-filter=".ahooga"><?= L::achat_brand_option2; ?></option>
                                        <option data-filter=".benno"><?= L::achat_brand_option3; ?></option>
                                        <option data-filter=".bzen"><?= L::achat_brand_option4; ?></option>
                                        <option data-filter=".conway"><?= L::achat_brand_option5; ?></option>
                                        <option data-filter=".douze"><?= L::achat_brand_option6; ?></option>
                                        <option data-filter=".hnf"><?= L::achat_brand_option7; ?></option>
                                        <option data-filter=".kayza"><?= L::achat_brand_option8; ?></option>
                                        <option data-filter=".orbea"><?= L::achat_brand_option9; ?></option>
                                        <option data-filter=".victoria"><?= L::achat_brand_option10; ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="widget-contact-form-utilisation"><?= L::achat_searchbar_use; ?></label>
                                    <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })" class="portfolio" data-filter-group="utilisation" name="widget-contact-form-utilisation" id="widget-bike-utilisation">
                                        <option data-filter="" value="*"><?= L::achat_use_option1; ?></option>
                                        <option data-filter=".villeetchemin"><?= L::achat_use_option2; ?></option>
                                        <option data-filter=".ville"><?= L::achat_use_option3; ?></option>
                                        <option data-filter=".toutchemin"><?= L::achat_use_option4; ?></option>
                                        <option data-filter=".pliant"><?= L::achat_use_option5; ?></option>
                                        <option data-filter=".speedpedelec"><?= L::achat_use_option6; ?></option>
                                        <option data-filter=".gravel"><?= L::achat_use_option7; ?></option>
                                        <option data-filter=".vtt"><?= L::achat_use_option8; ?></option>
                                        <option data-filter=".cargo"><?= L::achat_use_option9; ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="widget-contact-form-cadre"><?= L::achat_searchbar_cadre; ?></label>
                                    <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })" class="portfolio" data-filter-group="cadre" name="widget-contact-form-cadre" id="widget-bike-frame-type">
                                        <option data-filter="" value="*"><?= L::achat_cadre_option1; ?></option>
                                        <option data-filter=".m" value="M"><?= L::achat_cadre_option2; ?></option>
                                        <option data-filter=".f" value="F"><?= L::achat_cadre_option3; ?></option>
                                        <option data-filter=".h" value="H"><?= L::achat_cadre_option4; ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="widget-contact-form-electrique"><?= L::achat_searchbar_assist; ?></label>
                                    <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })" class="portfolio" data-filter-group="electrique" name="widget-contact-form-electrique" id="widget-bike-electric">
                                        <option data-filter="" value="*"><?= L::achat_assist_option1; ?></option>
                                        <option data-filter=".y"><?= L::achat_assist_option2; ?></option>
                                        <option data-filter=".n"><?= L::achat_assist_option3; ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="widget-contact-form-prix"><?= L::achat_searchbar_buyprice; ?></label>
                                    <select onchange="window.scrollTo({ top: 100, behavior: 'smooth' })" data-filter-group="prix" name="widget-contact-form-prix" id="widget-bike-price">
                                        <option data-filter="" value="*" selected><?= L::achat_buyprice_option1; ?></option>
                                        <option data-filter=".2000"><?= L::achat_buyprice_option2; ?></option>
                                        <option data-filter=".between-2000-3000"><?= L::achat_buyprice_option3; ?></option>
                                        <option data-filter=".between-3000-4000"><?= L::achat_buyprice_option4; ?></option>
                                        <option data-filter=".between-4000-5000"><?= L::achat_buyprice_option5; ?></option>
                                        <option data-filter=".5000"><?= L::achat_buyprice_option6; ?></option>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-12" id="promo-btn-block">
                                <a class="button rounded black-light button-3d effect icon-left" style="background-color: #ffc300; display:block; text-align: center;" href="bons-plans.php"><span><i class="fas fa-percent"></i><?= L::achat_bonsplans_btn; ?></span></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 catalog">
                        <h1 class="text-green"><?= L::achat_bikes_title; ?></h1>
                        <div class="grid"></div>

                        <!-- END: Portfolio Items -->

                    </div>
                </div>

                <button onclick="topFunction()" id="btn_goto_top_catalog" title="Go to top"><i class="fas fa-arrow-circle-up"></i></button>
            </div>
        </section>
        <!-- END: CONTENT -->
        <div class="modal fade" id="bikePicture" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 id="bikePicturetitle" class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row mb20">
                            <img id="bikePictureImage" class="img-responsive img-rounded" alt="" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-b" type="button"><?= L::achat_modal_close; ?></button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var bikes;

            function loadPortfolio() {

                var $grid = $('.grid').isotope({});

                //document.getElementById('bikeCatalog').innerHTML="";
                var utilisation = document.getElementById('widget-bike-utilisation').value;
                var frameType = document.getElementById('widget-bike-frame-type').value;
                var e = document.getElementById('widget-bike-price');
                var price = e.options[e.selectedIndex].value;
                var brand = document.getElementById('widget-bike-brand').options[document.getElementById('widget-bike-brand').selectedIndex].value;
                var e = document.getElementById('widget-bike-electric');
                var electric = e.options[e.selectedIndex].value;
                $.ajax({
                    url: 'apis/Kameo/load_portfolio.php',
                    type: 'get',
                    data: {
                        "action": "list"
                    },
                    success: function(response) {
                        if (response.response == 'error') {
                            $.notify({
                                message: response.message
                            }, {
                                type: 'danger'
                            });
                        }
                        if (response.response == 'success') {
                            var $grid = $('.grid').isotope({
                                itemSelector: '.grid-item',
                            });

                            var i = 0;
                            while (i < response.bikeNumber) {
                                if (response.bike[i].display == 'Y') {
                                    if (response.bike[i].frameType.toLowerCase() == "h") {
                                        var frameType = "Homme";
                                    } else if (response.bike[i].frameType.toLowerCase() == "m") {
                                        var frameType = "Mixte";
                                    } else if (response.bike[i].frameType.toLowerCase() == "f") {
                                        var frameType = "Femme";
                                    } else {
                                        var frameType = "undefined";
                                    }

                                    if (parseInt(response.bike[i].price) <= "2000") {
                                        var price = "2000";
                                    } else if (parseInt(response.bike[i].price) <= "3000") {
                                        var price = "between-2000-3000";
                                    } else if (parseInt(response.bike[i].price) <= "4000") {
                                        var price = "between-3000-4000";
                                    } else if (parseInt(response.bike[i].price) <= "5000") {
                                        var price = "between-4000-5000";
                                    } else {
                                        var price = "5000";
                                    }

                                    var temp = "\
                                    <div class=\"grid-item " + response.bike[i].brand.toLowerCase() + " " + response.bike[i].frameType.toLowerCase() + " " + response.bike[i].utilisation.toLowerCase().replace(/ /g, '') + " " + response.bike[i].electric.toLowerCase().replace(/ /g, '') + " " + price + "\" \">\
                                        <div class=\"portfolio-image effect social-links\">\
                                            <img src=\"images_bikes/" + response.bike[i].brand.toLowerCase().replace(/ /g, '-') + "_" + response.bike[i].model.toLowerCase().replace(/ /g, '-') + "_" + response.bike[i].frameType.toLowerCase() + "_mini.jpg\" alt=\"image_" + response.bike[i].brand.toLowerCase().replace(/ /g, '-') + "_" + response.bike[i].model.toLowerCase().replace(/ /g, '-') + "_" + response.bike[i].frameType.toLowerCase() + "\" class=\"lazy\">\
                                            <div class=\"image-box-content\">\
                                                <p>\
                                                    <a data-target=\"#bikePicture\" data-toggle=\"modal\" href=\"#\" onclick=\"updateBikePicture('" + response.bike[i].brand + "', '" + response.bike[i].model + "', '" + response.bike[i].frameType + "')\"><i class=\"fa fa-expand\"></i></a>\
                                                    <a href=\"offre.php?brand=" + response.bike[i].brand.toLowerCase() + "&model=" + response.bike[i].model.toLowerCase() + "&frameType=" + response.bike[i].frameType.toLowerCase() + "\"><i class=\"fa fa-link\"></i></a>\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class=\"portfolio-description\">\
                                            <a href=\"offre.php?brand=" + response.bike[i].brand.toLowerCase() + "&model=" + response.bike[i].model.toLowerCase() + "&frameType=" + response.bike[i].frameType.toLowerCase() + "\"><h4 class=\"title\">" + response.bike[i].brand + "</h4></a>\
                                            <p>" + (response.bike[i].model + " " + frameType).substr(0, 25) + "\
                                            <br>" + response.bike[i].utilisation + "\
                                            <br><b>Achat :" + Math.round(response.bike[i].price) + "  €</b>\
                                            <br>Coût brut :" + response.bike[i].leasingPrice + " €/mois";
                                    var textExplanation="Montant du leasing : "+response.bike[i].leasingPrice+" €/mois<br/>Impact salaire brut : "+Math.round(response.bike[i].impactOnGrossSalary*10)/10+" €/mois<br/><b>Impact salaire net : "+Math.round(response.bike[i].impactOnNetSalary*10)/10+" €/mois</b>";
                                    if(response.bike[i].impactBikeAllowance != 0 || response.bike[i].impactCarSavingMoney != 0){
                                      textExplanation = textExplanation.concat("<hr/>");
                                    }
                                    if(response.bike[i].impactBikeAllowance != 0){
                                      textExplanation = textExplanation.concat("<br/>Impact prime vélo : "+Math.round(response.bike[i].impactBikeAllowance*10)/10+" €/mois")
                                    }
                                    if(response.bike[i].impactCarSavingMoney != 0){
                                      textExplanation = textExplanation.concat("<br/>Economie véhicule : "+Math.round(response.bike[i].impactCarSavingMoney*10)/10+" €/mois")
                                    }
                                    if(response.bike[i].impactBikeAllowance != 0 || response.bike[i].impactCarSavingMoney != 0){
                                      textExplanation = textExplanation.concat("<hr/>");
                                    }
                                    if(response.bike[i].realImpact > 0){
                                      textExplanation = textExplanation.concat("<b>Coût réel : "+Math.round(response.bike[i].realImpact)+" €/mois</b>");
                                    }else{
                                      textExplanation = textExplanation.concat("<b class='text-green'>Gain réel : "+Math.abs(Math.round(response.bike[i].realImpact))+" €/mois</b>");
                                    }

                                    if(response.bike[i].realImpact > 0){
                                      temp=temp.concat("<br><b class=\"text-red\" data-toggle=\"popover\" data-html=\"true\" data-trigger=\"hover\" data-container=\"body\"  data-placement=\"top\" title=\"Détail calcul\" data-content=\""+textExplanation+"\">Cout réel : "+ Math.round(response.bike[i].realImpact)+" €/mois</b></p></div></div>");
                                    }else{
                                      temp=temp.concat("<br><b class=\"text-green\" data-toggle=\"popover\" data-html=\"true\" data-trigger=\"hover\" data-container=\"body\"  data-placement=\"top\" title=\"Détail calcul\" data-content=\""+textExplanation+"\">Cout réel : "+ Math.abs(Math.round(response.bike[i].realImpact))+" €/mois</b></p></div></div>");
                                    }
                                    var $item = $(temp);
                                    // add width and height class
                                    $item.addClass('grid-item--width3').addClass('grid-item--height3');
                                    $grid.append($item)
                                        // add and lay out newly appended elements
                                        .isotope('appended', $item);
                                }
                                i++;
                            }
                            $(function () {
                              $('[data-toggle="popover"]').popover()
                            })

                            var filters = {};

                            $('.portfolio').on('change', function(event) {
                                var $cible = $(event.currentTarget);
                                var filterGroup = $cible.attr('data-filter-group');
                                filters[filterGroup] = $(this).children("option:selected").attr('data-filter');
                                var filterValue = concatValues(filters);
                                $grid.isotope({
                                    filter: filterValue
                                });
                            });

                            function concatValues(obj) {
                                var value = '';
                                for (var prop in obj) {
                                    value += obj[prop];
                                }
                                return value;
                            }

                            $('.grid').isotope("layout");

                        }

                    }
                });
            }
            loadPortfolio();

            function updateBikePicture(brand, model, frameType) {

                document.getElementById('bikePicturetitle').innerHTML = brand + " " + model;
                document.getElementById('bikePictureImage').src = "images_bikes/" + brand.toLowerCase().replace(/ /g, '-') + "_" + model.toLowerCase().replace(/ /g, '-') + "_" + frameType.toLowerCase() + ".jpg";

            }

            window.addEventListener( "pageshow", function ( event ) {
              $("#widget-bike-brand").val("*");
              $("#widget-bike-utilisation").val("*");
              $("#widget-contact-form-cadre").val("*");
              $("#widget-bike-electric").val("*");
              $("#widget-contact-form-prix").val("*");
            });
        </script>
        <?php include 'include/footer.php'; ?>
    </div>
    <!-- END: WRAPPER -->

    <!-- Theme Base, Components and Settings -->
    <script src="js/theme-functions.js"></script>
    <!-- Search Bar Scroll Fixed -->
    <script src="js/achat_scroll.js"></script>
    <!-- Scroll to top button -->
    <script src="js/achat_scroll_to_top.js"></script>
    <!-- TB Popup Redirection -->
    <script src="js/tb_popup.js"></script>
</body>

</html>
