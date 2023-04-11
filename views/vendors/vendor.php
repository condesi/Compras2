<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
    .mascara{
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background-color: black;
        opacity: 0.5;
        z-index: 99999 !important;
        display: none;
    }
    .loader {
      border: 16px solid #f3f3f3 !important;
      border-radius: 50% !important;
      border-top: 16px solid #3498db !important;
      width: 120px !important;
      height: 120px !important;
      -webkit-animation: spin 2s linear infinite; /* Safari */
      animation: spin 2s linear infinite;
      margin: 0px auto !important;
      margin-top: 10% !important;
      display: block;
    }
    /* Safari */
    @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
</style>
<div class="mascara">
    <div class="loader"></div>
</div>
<div id="wrapper" class="customer_profile">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <?php if(isset($client) && $client->registration_confirmed == 0 && is_admin()){ ?>
               <div class="alert alert-warning">
                  <?php echo _l('customer_requires_registration_confirmation'); ?>
                  <br />
                  <a href="<?php echo admin_url('clients/confirm_registration/'.$client->userid); ?>"><?php echo _l('confirm_registration'); ?></a>
               </div>
            <?php } else if(isset($client) && $client->active == 0 && $client->registration_confirmed == 1){ ?>
            <div class="alert alert-warning">
               <?php echo _l('customer_inactive_message'); ?>
               <br />
               <a href="<?php echo admin_url('clients/mark_as_active/'.$client->userid); ?>"><?php echo _l('mark_as_active'); ?></a>
            </div>
            <?php } ?>
            <?php if(isset($client) && (!has_permission('purchase_vendors','','view') && is_vendor_admin($client->userid))){?>
            <div class="alert alert-info">
               <?php echo _l('customer_admin_login_as_client_message',get_staff_full_name(get_staff_user_id())); ?>
            </div>
            <?php } ?>
         </div>
         <?php if($group == 'profile'){ ?>
         <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
            <button class="btn btn-info only-save customer-form-submiter">
            <?php echo _l( 'submit'); ?>
            </button>
            <?php if(!isset($client)){ ?>
            <button class="btn btn-info save-and-add-contact customer-form-submiter">
            <?php echo _l( 'save_customer_and_add_contact'); ?>
            </button>
            <?php } ?>
         </div>
         <?php } ?>
         <?php if(isset($client)){ ?>
         <div class="col-md-3">
            <div class="panel_s mbot5">
               <div class="panel-body padding-10">
                  <h4 class="bold">
                     #<?php echo html_entity_decode($client->userid . ' ' . $title); ?>
                    
                     
                  </h4>
               </div>
            </div>
            <?php $this->load->view('vendors/tabs'); ?>
         </div>
         <?php } ?>
         <div class="col-md-<?php if(isset($client)){echo 9;} else {echo 12;} ?>">
            <div class="panel_s">
               <div class="panel-body">
                  <?php if(isset($client)){ ?>
                  <?php echo form_hidden('isedit'); ?>
                  <?php echo form_hidden('userid', $client->userid); ?>
                  <div class="clearfix"></div>
                  <?php } ?>
                  <div>
                     <div class="tab-content">
                           <?php $this->load->view((isset($tabs) ? $tabs['view'] : 'vendors/groups/profile')); ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php if($group == 'profile'){ ?>
         <div class="btn-bottom-pusher"></div>
      <?php } ?>
   </div>
</div>
<?php init_tail(); ?>

<?php require 'modules/purchase/assets/js/vendor_js.php';?>
<script>
    $(document).ready(function(){
        $("#vat").blur(function(){
            let value = $(this).val();
            if(value){
                $(".mascara").fadeIn(200);
                $.get("https://dniruc.apisperu.com/api/v1/ruc/"+value+"?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImVucmlxdWVhbWV6QGhvdG1haWwuY29tIn0.kI_XnoVvY1QMEeqqDwJoITIEJ1o6ojuZO5IMz75G03Y", {}, function(response){
                    let data = response;
                     $(".mascara").fadeOut(200);
                    $("#company").val(data.razonSocial);
                    $("#phonenumber").val(data.telefonos[0]);
                    $("#address").val(data.direccion);
                    $("#city, #shipping_city").val(data.distrito);
                    $("#state, #shipping_state").val(data.provincia);
                    $("#zip, #shipping_zip").val(data.ubigeo);
                    $("#shipping_country").val(173).trigger("change");
                }).fail(function(error){
                    console.log(error);
                    $("#company, #phonenumber, #address, #city, #state, #zip, #shipping_city, #shipping_state, #shipping_zip, #shippin_country").val("");
                });
            }
        });
    });
</script>
</body>
</html>
