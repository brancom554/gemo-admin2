<?php @header('Content-Type: text/javascript; charset=utf-8');?>

// ************************************************
// Shopping Cart API
// ************************************************
var shoppingCart = (function() {
    // =============================
    // Private methods and propeties
    // =============================
    cart = [];

    // Constructor
    function Item(id, name, price, imgurl, storeCode, storeName, count, qte_stock,productemaildirecteur,productemailmagasin) {
        this.id = id;
        this.name = name;
        this.price = price;
        this.imgurl = imgurl;
        this.storeCode = storeCode;
        this.storeName = storeName;
        this.count = count;
        this.qte_stock = qte_stock;
        this.productemaildirecteur = productemaildirecteur;
        this.productemailmagasin = productemailmagasin;
    }

    // Save cart
    function saveCart() {
        sessionStorage.setItem('shoppingCart', JSON.stringify(cart));
    }

    // Load cart
    function loadCart() {
        cart = JSON.parse(sessionStorage.getItem('shoppingCart'));
    }
    if (sessionStorage.getItem("shoppingCart") != null) {
        loadCart();
    }


    // =============================
    // Public methods and propeties
    // =============================
    var obj = {};

    // Add to cart
    obj.addItemToCart = function(id, name, price, imgurl, storeCode, storeName, count, qte_stock,productemaildirecteur,productemailmagasin) {
        for (var item in cart) {
            if (cart[item].id === id) {
                cart[item].count++;
                saveCart();
                return;
            }
        }
        var item = new Item(id, name, price, imgurl, storeCode, storeName, count, qte_stock,productemaildirecteur,productemailmagasin);
        cart.push(item);
        saveCart();

    }

    // Set count from item
    obj.setCountForItem = function(id, count) {
        for (var i in cart) {
            if (cart[i].id === id) {
                cart[i].count = count;
                break;
            }
        }
    };

    // Remove item from cart
    obj.removeItemFromCart = function(id) {
        for (var item in cart) {
            if (cart[item].id === id) {
                cart[item].count--;
                if (cart[item].count === 0) {
                    cart.splice(item, 1);
                }
                break;
            }
        }
        saveCart();
    }

    function retrieveItemByMagCode(_code) {
        return function(element) {
            return element.storeCode == _code;
        }
    }

    // Remove all Store's items from cart
    obj.removeStoreItem = function(storeCode) {

        var products = cart.filter(retrieveItemByMagCode(storeCode));
        console.log(products);

        for (var prod_id in products) {
            for (var item in cart) {
                if (cart[item].id === products[prod_id].id) {
                    cart.splice(item, 1);
                }
            }
        }
        saveCart();
    }

    // Remove all items from cart
    obj.removeItemFromCartAll = function(id) {
        for (var item in cart) {
            if (cart[item].id === id) {
                cart.splice(item, 1);
                break;
            }
        }
        saveCart();
    }

    // Clear cart
    obj.clearCart = function() {
        cart = [];
        saveCart();
    }

    // Count cart 
    obj.totalCount = function() {
        var totalCount = 0;
        for (var item in cart) {
            totalCount += cart[item].count;
        }
        return totalCount;
    }

    // Total cart
    obj.totalCart = function() {
        var totalCart = 0;
        for (var item in cart) {
            totalCart += cart[item].price * cart[item].count;
        }
        return Number(totalCart.toFixed(2));
    }

    // List cart
    obj.listCart = 
        function() {
        var cartCopy = [];
        for (i in cart) {
            item = cart[i];
            itemCopy = {};
            for (p in item) {
                itemCopy[p] = item[p];

            }
            itemCopy.total = Number(item.price * item.count).toFixed(2);
            cartCopy.push(itemCopy)
        }
        return cartCopy;
    }

    // List magasin
    obj.magasinslist = 
        function() {
            var magasinCopy = [];
            for (i in cart) {
                item = cart[i];
                itemCopy = {};
                for (p in item) {
                    itemCopy[p] = item[p];
                }
                magasinCopy.push(itemCopy)
            }
            return magasinCopy;
    }

    // cart : Array
    // Item : Object/Class
    // addItemToCart : Function
    // removeItemFromCart : Function
    // removeItemFromCartAll : Function
    // clearCart : Function
    // countCart : Function
    // totalCart : Function
    // listCart : Function
    // saveCart : Function
    // loadCart : Function

    return obj;
})();


//****************** Notify user when product is add to cart ***************************/
var message;
    message = function(){
     jSuccess(
        "<i class='fa fa-check-square-o' style='padding-right:6px'></i> Produit ajouté au panier", 
         {
            HorizontalPosition: "right",
            VerticalPosition: "bottom",
            ShowOverlay:  false,
            TimeShown: 2000,
            OpacityOverlay: 0.5,
             MinWidth: 250
     });    
    }
 
 //message();


// *****************************************
// Triggers / Events
// ***************************************** 
// Add item
$('.add-to-cart').click(function(event) {
    event.preventDefault();
   
    message();


    var id = $(this).data('productid');
    var name = $(this).data('productname');
    var img = $(this).data('productimage');
    var price = Number($(this).data('productprice').replace(",",""));
    var storeCode = $(this).data('productstorecode');
    var storeName = $(this).data('productstorename');
    var qte_stock = $(this).data('qtestock');
    var productemaildirecteur = $(this).data('productemaildirecteur');
    var productemailmagasin = $(this).data('productemailmagasin');
    shoppingCart.addItemToCart(id, name, price, img, storeCode, storeName, 1, qte_stock,productemaildirecteur,productemailmagasin);
    displayCart();
});

// Clear items
$('.clear-cart').click(function() {
    shoppingCart.clearCart();
    displayCart();
});


function displayCart() {
    var cartArray = shoppingCart.listCart();
    var output = "";
    for (var i in cartArray) {

      output += '<div class="row no-gutters g-pb-5 g-mb-10">' +
                  '<div class="col-3 g-pr-5">'+
                    '<img class="img-fluid" src="'+cartArray[i].imgurl+'" alt="'+cartArray[i].name+'">'+
                  '</div>'+
                  '<div class="col-7 g-mt-5 g-pr-5">'+
                    '<h6 class="g-font-weight-400 g-font-size-default">'+
                      '<span> <a class="g-color-black g-color-primary--hover g-text-underline--none--hover" href="#">'+cartArray[i].name+' </a></span>'+
                      ' (<span class="g-color-primary g-font-size-12">'+cartArray[i].count+' x € '+cartArray[i].price+' </span>)'+
                    '</h6>'+
                  '</div>'+
                  '<div class="col-2 g-mt-5">'+
                    '<a href="#" class="delete-item text-danger" data-productid="'+cartArray[i].id +'">&times;</a>'+
                  '</div>'+
                '</div>';
    }
    $('.show-cart').html(output);
    $('.total-cart').html(shoppingCart.totalCart());
    $('.total-count').html(shoppingCart.totalCount());

    if(typeof displayCheckout === "function"){
        displayCheckout();
    }
}

// Delete item button

$('.show-cart').on("click", ".delete-item", function(event) {
    var productid = $(this).data('productid')
    shoppingCart.removeItemFromCartAll(productid);
    displayCart();
})


// -1
$('.show-cart').on("click", ".minus-item", function(event) {
    var productid = $(this).data('productid')
    shoppingCart.removeItemFromCart(productid);
    displayCart();
})
// +1
$('.show-cart').on("click", ".plus-item", function(event) {
    var productid = $(this).data('productid')
    shoppingCart.addItemToCart(productid);
    displayCart();
})

// Item count input
$('.show-cart').on("change", ".item-count", function(event) {
    var productid = $(this).data('productid');
    var count = Number($(this).val());
    shoppingCart.setCountForItem(productid, count);
    displayCart();
});

displayCart();