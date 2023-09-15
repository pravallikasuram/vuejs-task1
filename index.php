<?php
include('bootstrap.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product List with Pagination</title>
    <style>
    .card-header {
            background-color: #343a40;
            color: #ffffff;
        }

        .header {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }    
    </style>
  </head>
  <body>
    
    <div id="app" class="container mt-5">
      <div class="row mb-3">
        <div class="col-md-8">
          <h1 class="display-4">Product List</h1>
        </div>
        <!-- Input fields for adding a new product -->
        <br>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Add a Product
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <input v-model="newProduct.name" class="form-control" placeholder="Product Name" />
                        <input v-model="newProduct.description" class="form-control" placeholder="Product Description" />
                        <button @click="addProduct" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </div>
       

      <!-- Product table -->
      <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" >Product Details</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="bg-dark text-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        <tbody>
                        <tr v-for="(product, index) in paginatedProducts" :key="index">
                            <td>{{ product.id }}</td>
                            <td>{{ product.name }}</td>
                            <td>{{ product.description }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" @click="deleteProduct(product.id)">Delete</button>
                            </td>
                        </tr>
                        </tbody>
                        </table>
                    </div>  
                </div>
            </div>
        </div>
      </div>
        
        
      <!-- Pagination controls -->
      <nav>
        <ul class="pagination justify-content-center">
          <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <a class="page-link"  @click="prevPage">Previous</a>
          </li>
          <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: page === currentPage }">
            <a class="page-link"  @click="gotoPage(page)">{{ page }}</a>
          </li>
          <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <a class="page-link"  @click="nextPage">Next</a>
          </li>
        </ul>
      </nav>
    </div>
    </div>    
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
      new Vue({
        el: "#app",
        data: {
          newProduct: {
            name: "",
            description: "",
          },
          products: [],
          currentPage: 1,  //current page
          itemsPerPage: 5, // Number of items to display per page
        },
        computed: {
          totalPages() {
            return Math.ceil(this.products.length / this.itemsPerPage);
          },
          paginatedProducts() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.products.slice(start, end);
          },
        },
        mounted() {
          this.getProducts();
        },
        methods: {
          getProducts() {
            axios
              .get("api/products.php")
              .then((response) => {
                this.products = response.data;
              })
              .catch((error) => {
                console.error(error);
              });
          },
          addProduct() {
            axios
              .post("api/products.php", this.newProduct)
              .then((response) => {
                this.getProducts();
                this.newProduct = {
                  name: "",
                  description: "",
                };
              })
              .catch((error) => {
                console.error(error);
              });
          },
          deleteProduct(productId) {
            axios
              .delete(`api/products.php?id=${productId}`)
              .then((response) => {
                this.getProducts();
              })
              .catch((error) => {
                console.error(error);
              });
          },
          nextPage() {
            if (this.currentPage < this.totalPages) {
              this.currentPage++;
            }
          },
          gotoPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
          prevPage() {
            if (this.currentPage > 1) {
              this.currentPage--;
            }
          },
        },
      });
    </script>
  </body>
</html>
