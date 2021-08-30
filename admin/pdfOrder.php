<?php require_once 'includes/includes.php'; ob_start();?>
<style type="text/css">
.header{
    display: block;
    height:70px;
    background: #ddd url(../img/logo.png) 5px 5px no-repeat;
    padding:10px 10px 10px 200px;
}
.header p{
    margin:0;
    color:#000;
}
.footer p{
    margin:0;
    font-size:10px;
    color:#999;
}
.footer hr{
    color:#999;
}
h4{
    text-align: right;
}
h1{
    text-transform: uppercase;
    font-size:18px;
    text-align: center;
    color:#444;
    margin:40px;
}
.client{
    margin-left: 400px;
    padding:10px;
    border:1px dotted #999;
}
.client p{
    margin:0;
}

table{
    width:100%;
}
table thead th{
    width:11%;
    background: #000;
    color:#FFF;
    padding:5px;
    text-align: center;
}
table thead th.large{
    width:56%;
    text-align: left;
}
table tbody tr td{
    padding:8px 5px;
    border:1px solid #999;
    text-align: center;
}
table tbody tr td.large{
    text-align: left;
}
table tr.total{
       background: #000;
    color:#FFF;
}

</style>
<?php 
  $order = $DB->query("SELECT * FROM orders INNER JOIN users On user_id = users.id WhERE orders.id=:id",array('id'=>$_GET['id']));
  $order=$order[0];
  $donnees = (array)(unserialize($order->datas));
 ?>

<page footer="date;pagination" backtop="120px" backbottom="100px">
    <page_header>
       <div class="header">
            <p><strong>Athakim.fr</strong></p>
            <p>1 rue de verdun 75001 Paris</p>
            <p><strong>Tél :</strong> 01 47 00 00 00</p>
            <p><strong>Email:</strong>contact@athakim.fr</p>
             <p><strong>SIRET:</strong> 404 833 048 00022</p> 
       </div>
    </page_header>
    <page_footer>
        <div class="footer">
            <hr/>
            <p>http://wwww.heightech-Shop.fr</p>
            page [[page_cu]]/[[page_nb]]
        </div>
    </page_footer>
    <h4>Paris le ,<?php echo date('d M Y') ?></h4>
    <div class="client">
        <p><strong><?php echo $order->nom; ?></strong></p>
        <p><?php echo $order->adresse ?></p>
        <p><?php echo $order->codepostale ?> - <?php echo $order->ville; ?></p>
        <p><strong>Tél :</strong> 01 47 00 00 00</p>
        <p><strong>Email:</strong><?php echo $order->email ?></p>   
    </div>
    <h1>Facture no:<?php echo $_GET['id'] ?></h1>
    <p>date de la facture : <?php echo $order->created; ?></p>
        <table>
            <thead>
                <tr>                    
                    <th>Réf</th>
                    <th class="large">Désignation</th>
                    <th>Qte</th>
                    <th>P .U</th>
                    <th>Prix total</th>
                </tr>
            </thead>
            <tbody>
             <?php 
             $total =0;$qte=0;

         for($i=1;$i<=$donnees['num_cart_items'];$i++) {?>
            <tr>
              <td><?php echo $donnees['item_number'.$i] ?></td>
              <td><?php echo $donnees['item_name'.$i] ?></td>
              <td><?php echo $donnees['quantity'.$i] ?></td>
              <td><?php echo $donnees['mc_gross_'.$i] ?></td>
               <td><?php echo $donnees['mc_gross_'.$i]* $donnees['quantity'.$i] ?></td>
            </tr>
            <?php 
                $total +=  $donnees['mc_gross_'.$i]* $donnees['quantity'.$i];
                $qte+= $donnees['quantity'.$i];?>
         <?php }?>
             
            <tr class="total">
                <td colspan="2">Total</td>
                <td><?php echo $qte ?></td>
                <td></td>
                <td><?php echo $total ?>€</td>
            </tr>
            </tbody>
        </table>

</page>
<?php
$content= ob_get_clean();
require_once('../html2pdf/html2pdf.class.php');
try{
    $pdf = new HTML2PDF('P','A4','fr');
    $pdf->pdf->SetDisplayMode('fullpage');

    $pdf->pdf->SetTitle('Ma facture ...');
    $pdf->pdf->SetAuthor('athakim');

    $pdf->pdf->SetProtection(array('print'));

    $pdf->writeHTML($content);
    $pdf->Output('facture_heightechShp_'.$_GET['id'].'.pdf','D');
}catch(HTML2PDF_exception $e){
    echo $e->getMessage();
    exit;
}