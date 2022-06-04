<div class="admin_sidebar text-white">
   <div class="sidebar_header">
      <h5>Dashboard of</h5>
      <h3>Future Cell Ltd</h3>
   </div>
   <div class="admin_sidebar_middle">
      <div class="navigations" role="navigation">
         <div class="single_navigation">
            <ul>
                  <li><a href="{{ url('/manager') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            </ul>
         </div>

         <div class="single_navigation">
            <ul>
               <li class="has_child">
                  <span><i class="fa fa-clone"></i> Dynamic Pages</span>
                  <ul>
                     <li><a href="{{ url('/manager/pages') }}">All Pages</a></li>
                     <li><a href="{{ url('/manager/pages/create') }}">Add Page</a></li>
                  </ul>
               </li>
               <li class="has_child">
                  <span><i class="fa fa-sticky-note-o"></i> Static Pages</span>
                  <ul>
                     <li><a href="{{ url('/manager/static-pages/home') }}">Home</a></li>
                     <li><a href="{{ url('/manager/static-pages/store') }}">Store page</a></li>
                     <!-- <li><a href="{{ url('/manager/static-pages/category') }}">Category page</a></li>
                     <li><a href="{{ url('/manager/static-pages/brand') }}">Brand page</a></li>
                     <li><a href="{{ url('/manager/static-pages/search') }}">Search page</a></li> -->
                     <li><a href="{{ url('/manager/static-pages/contact') }}">Contact us</a></li>
                     <li><a href="{{ url('/manager/static-pages/about') }}">About</a></li>
                     <li><a href="{{ url('/manager/static-pages/terms-conditions') }}">Terms & Conditions</a></li>
                  </ul>
               </li>
            </ul>
         </div>

         <div class="single_navigation">
            <ul>
               <li class="has_child">
                  <span><i class="fa fa-shopping-cart"></i> Products</span>
                  <ul>
                     <li><a href="{{ url('/manager/products') }}">All Products</a></li>
                     <li><a href="{{ url('/manager/products/add') }}">Add Products</a></li>
                     <li><a href="{{ url('/manager/products/customers') }}">Customers</a></li>
                     <li><a href="{{ url('/manager/products/coupons') }}">Coupons</a></li>
                     <li><a href="{{ url('/manager/products/securities') }}">Securities</a></li>
                     <li><a href="{{ url('/manager/products/delivery-options') }}">Delivery options</a></li>

                     <SuperAdminArea><li><a href="{{ url('/manager/products/settings') }}">Settings</a></li></SuperAdminArea>
                  </ul>
               </li>
               
               <li><a href="{{ url('/manager/products/orders') }}"><i class="fa fa-shopping-basket"></i> Orders</a></li>
            </ul>
         </div>

         <div class="single_navigation">
            <ul>
               <li class="has_child">
                  <span><i class="fa fa-certificate"></i> Brands</span>
                  <ul>
                     <li><a href="{{ url('/manager/brands') }}">All brands</a></li>
                     <li><a href="{{ url('/manager/brands/add') }}">Add brand</a></li>
                  </ul>
               </li>
               <li class="has_child">
                  <span><i class="fa fa-list-ul"></i> Category</span>
                  <ul>
                     <li><a href="{{ url('/manager/category') }}">All category</a></li>
                     <li><a href="{{ url('/manager/category/add') }}">Add category</a></li>
                  </ul>
               </li>
               <li class="has_child">
                  <span><i class="fa fa-square-o"></i> Models</span>
                  <ul>
                     <li><a href="{{ url('/manager/models') }}">All model</a></li>
                     <li><a href="{{ url('/manager/models/add') }}">Add model</a></li>
                  </ul>
               </li>
               <li class="">
                  <a href="{{ url('/manager/colors') }}">
                     <i class="fa fa-yelp"></i> Colors
                  </a>
               </li>
               <li>
                  <a href="{{ url('/manager/sizes') }}">
                     <i class="fa fa-balance-scale"></i> Sizes
                  </a>
               </li>
            </ul>
         </div>

         <div class="single_navigation">
            <ul>
               <li>
                  <a href="{{ url('/manager/contacts') }}"><i class="fa fa-envelope-o"></i>Contacts</a>
               </li>
               <li>
                  <a href="{{ url('/manager/subscribers') }}"><i class="fa fa-address-book-o"></i>Subscribers</a>
               </li>
            </ul>
         </div>

         <div class="single_navigation">
            <ul>
               <li class="has_child">
                  <span><i class="fa fa-cogs"></i> Settings</span>
                  <ul>
                     <li><a href="{{ url('/manager/settings') }}">Settings</a></li>
                     <li><a href="{{ url('/manager/settings/header') }}">Header</a></li>
                     <li><a href="{{ url('/manager/settings/footer') }}">Footer</a></li>
                  </ul>
               </li>
            </ul>
         </div>

         <div class="single_navigation">
            <ul>
               <li>
                  <a href="{{ url('/manager/import-export') }}"><i class="fa fa-sign-in"></i> Import / Export</a>
               </li>
            </ul>
         </div>

         <div class="single_navigation">
            <ul>
               <li class="has_child">
                  <span><i class="fa fa-users"></i> Admins</span>
                  <ul>
                     <li><a href="{{ url('/manager/admins') }}">All admins</a></li>
                     <li><a href="{{ url('/manager/admins/create') }}">Add new</a></li>
                     <li><a href="{{ url('/manager/admins/profile') }}">Profile</a></li>
                  </ul>
               </li>
            </ul>
         </div>

      </div>
   </div>
   <div class="admin_sidebar_footer">
      <a class="logout_link btn btn-dark btn-md my-2" href="{{ url('/manager/logout') }}"> <i class="fa fa-power-off"></i> Logout</a>
   </div>
</div>