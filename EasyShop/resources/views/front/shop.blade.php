@extends('front.master')

@section('content')
<style>
    .brandLi{
        padding:10px;
    }
    .brandLi b{ font-size:16px; color:#FE980F}
    </style>
    
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    
    $(function () {
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 100,
            values: [15, 65],
            slide: function (event, ui) {

                $("#amount_start").val(ui.values[ 0 ]);
                $("#amount_end").val(ui.values[ 1 ]);
                var start = $('#amount_start').val();
                var end = $('#amount_end').val();

                $.ajax({
                    type: 'get',
                    dataType: 'html',
                    url: '',
                    data: "start=" + start + "& end=" + end,
                    success: function (response) {
                        console.log(response);
                        $('#updateDiv').html(response);
                    }
                });
            }
        });
        
        $('.try').click(function(){
            
            //alert('hardeep');
            
            var brand = [];
            $('.try').each(function(){
                if($(this).is(":checked")){
                    
                    brand.push($(this).val());
                }
            });
            Finalbrand  = brand.toString();
           
            $.ajax({
                    type: 'get',
                    dataType: 'html',
                    url: '',
                    data: "brand=" + Finalbrand,
                    success: function (response) {
                        console.log(response);
                        $('#updateDiv').html(response);
                    }
                });
           
        });
       });
</script>
<section id="advertisement">
    <div class="container">
        <img src="{{asset('theme/images/shop/advertisement.jpg')}}" alt="" />
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="left-sidebar">
                  
                    <div class="price-range"><!--price-range-->
                              
                                <div class="well">
                                   <h2>Price Range</h2>
                                    <div id="slider-range"></div>
                                    <br>
                                    <b class="pull-left">$
                                        <input size="2" type="text" id="amount_start" name="start_price" 
                                               value="15" style="border:0px; font-weight: bold; color:green" readonly="readonly" /></b>

                                    <b class="pull-right">$ 
                                        <input size="2" type="text" id="amount_end" name="end_price" value="65"
                                               style="border:0px; font-weight: bold; color:green" readonly="readonly"/></b> 
                                   </div> 

                            </div><!--/price-range-->

                    <div class="brands_products"><!--brands_products-->                      
                        <div class="brands-name">
                              <h2>Brands</h2>
                                <ul class="nav nav-pills nav-stacked">
                                  
                                    <?php $cats = DB::table('pro_cat')->orderby('name', 'ASC')->get();?>
                                    
                                    @foreach($cats as $cat)                                    
                                    <li class="brandLi"><input type="checkbox" id="brandId" value="{{$cat->id}}" class="try"/>
                                 <span class="pull-right">({{App\products::where('cat_id',$cat->id)->count()}})</span> 
                                  <b>  {{ucwords($cat->name)}}</b></li>
                                   @endforeach
                                 <?php /*   <li><a href=""> <span class="pull-right">(56)</span>Grüne Erde</a></li>
                                    <li><a href=""> <span class="pull-right">(27)</span>Albiro</a></li>
                                    <li><a href=""> <span class="pull-right">(32)</span>Ronhill</a></li>
                                    <li><a href=""> <span class="pull-right">(5)</span>Oddmolly</a></li>
                                    <li><a href=""> <span class="pull-right">(9)</span>Boudestijn</a></li>
                                    <li><a href=""> <span class="pull-right">(4)</span>Rösch creative culture</a></li>
                                  * */?>
                                  
                               </ul>
                        </div>
                    </div><!--/brands_products-->

                    <div class="shipping text-center"><!--shipping-->
                        <img src="{{url('../')}}/theme/images/home/shipping.jpg" alt="" />
                    </div><!--/shipping-->

                </div>
            </div>

            <div class="col-sm-9 padding-right"  id="updateDiv" >
               
                 <div class="features_items"> <!--features_items-->
                      <b> Total Products</b>:  {{$Products->total()}}
                    <h2 class="title text-center">
                       <?php
                        if (isset($msg)) {
                            echo $msg;
                        } else {
                            ?> Features Item <?php } ?> </h2>

                    <?php if ($Products->isEmpty()) { ?>
                        sorry, products not found
                    <?php } else { ?>
                        @foreach($Products as $product)
                        <div class="col-sm-4" >
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <a href="{{url('/product_details')}}">
                                            <img src="<?php echo $product->pro_img; ?>" alt="" />
                                        </a>
                                        <h2 id="price">$<?php echo $product->pro_price; ?></h2>

                                        <p><a href="{{url('/product_details')}}"><?php echo $product->pro_name; ?></a></p>
                                        <a href="{{url('/cart/addItem')}}/<?php echo $product->id; ?>" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                    </div>
                                    <a href="{{url('/product_details')}}/<?php echo $product->id; ?>">
                                        <div class="product-overlay">
                                            <div class="overlay-content">
                                                <h2>$<?php echo $product->pro_price; ?></h2>
                                                <p><?php echo $product->pro_name; ?></p>
                                                <a href="{{url('/cart/addItem')}}/<?php echo $product->id; ?>" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                            </div>
                                        </div></a>
                                </div>
                                <div class="choose">
                                    <?php
                                    $wishData = DB::table('wishlist')->leftJoin('products', 'wishlist.pro_id', '=', 'products.id')->where('wishlist.pro_id', '=', $product->id)->get();
                                    $count = App\wishList::where(['pro_id' => $product->id])->count();
                                    ?>

                                    <?php if ($count == "0") { ?>
                                        <form action="{{url('/addToWishList')}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{$product->id}}" name="pro_id"/>
                                            <p align="center">
                                                <input type="submit" value="Add to WishList" class="btn btn-primary"/>
                                            </p>
                                        </form>
                                    <?php } else { ?>
                                        <h5 style="color:green"> Added to <a href="{{url('/WishList')}}">wishList</a></h5>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>

                        @endforeach
                    <?php } ?>


                </div>
                <ul class="pagination">
                    {{ $Products->links()}}
                </ul>
            </div><!--features_items-->

        </div>
    </div>
</div>
</section>
@endsection