<template>
  <section>
    <!-- Modal for creating new entity -->
    <btn type="primary" @click="openModal1=true">Create Entity</btn>
    <div class="form-group has-feedback has-feedback-left">
         <input type="text" class="form-control" v-on:input="fetchBusiness()" v-model="search" style="margin-top:15px;" placeholder="Search">
        <i class="glyphicon glyphicon-search form-control-feedback"></i>
    </div>
    <div v-if="Object.keys(noResult).length > 0">
        <span class="alert-danger">No Results Found</span>
    </div>
      <form @submit.prevent="addBusiness">
        <modal v-model="openModal1" title="Modal Title" size="lg" :dismiss-btn="false">
            <div class="form-group">
                <label>Business Name</label>
                <input type="text" v-model="business.businessName" class="form-control" placeholder="Business Name">
                <span class="alert-danger" v-show="errors.has('businessName')" v-text="errors.get('businessName')"></span>
            </div>
             
            <div class="form-group">
                <label>Business Address</label>
                <input type="text"  v-model="business.businessAddress" class="form-control" placeholder="Business Address">
                <span class="alert-danger"  v-show="errors.has('businessAddress')" v-text="errors.get('businessAddress')"></span>
            </div>
                <div class="form-group">
                    <label>Business Types</label>
                <select class="form-control" v-model="business.businessType">
                    <option value="Laundry Shop">Laundry Shop</option>
                    <option value="Computer Shop">Computer Shop</option>
                </select>
                <span class="alert-danger"  v-show="errors.has('businessType')" v-text="errors.get('businessType')"></span>
            </div>
            <div slot="footer">
                <button type="button" class="btn btn-default" @click="setFalse()">Close</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </modal>
    </form>

     <!-- Viewing of Entity -->
    <div class="panel panel-warning" v-for="business in businesses" :key="business.id" style="margin-top:15px;">
        <button type="button" class="btn btn-link pull-right" @click="deleteBusiness(business.id)">Delete</button>
        <button type="button" class="btn btn-link pull-right" @click="editBusiness(business.id)">Edit</button>
        <div class="panel-heading">
            <h3 class="panel-title"> {{ business.businessName}} </h3>
        </div>
        <div class="panel-body">
            Business Address : {{ business.businessAddress}} <br>
            Business Type : {{ business.businessType}} <br>
            <hr>
        <small>Created At {{ business.created_at }}</small>
        </div>
    </div>
    <small class="pull-right">{{pagination.current_page}} of {{pagination.last_page}}</small>
    <nav aria-label="...">
        <ul class="pager">
            <li :class="[{disabled: !pagination.prev_page_url}]" ><a href="#" @click="!!pagination.prev_page_url && fetchBusiness(pagination.prev_page_url)">Previous</a></li>
            <li :class="[{disabled: !pagination.next_page_url}]" ><a href="#" @click="!!pagination.next_page_url && fetchBusiness(pagination.next_page_url)">Next</a></li>
        </ul>
    </nav>
  </section>
</template>
<script>
class Errors 
{
    constructor()
    {
        this.errors = { };
    }

    //retrieve error message
    get(field)
    {   
        if(this.errors[field])
        {
            return this.errors[field][0];
        }
    }
    //check if field has error
    has(field) 
    {
        return this.errors.hasOwnProperty(field);
    }
    //record the errors
    record(errors)
    {
        this.errors = errors.errors;
    }
    //check if there is error
    any() 
    {
        return Object.keys(this.errors).length > 0;
    }
    //clear error
    clear(field) 
    {
        if (field) {
            delete this.errors[field];
            return;
        }
        this.errors = {};
    }
}
axios.defaults.baseURL = 'http://localhost/practice/public/';
  export default {
    data () {
      return {
        businesses: [],
        noResult: {},
        business: {
            id: '',
            businessAddress: '',
            businessType: '',
        },
        businessName: '',
        pagination: {},
        search: '',
        openModal1: false,
        edit: false,
        errors: new Errors()
      }
    },

    created()
    {
        this.fetchBusiness();
    },

    methods:
    {
        setFalse:function()
        {
            this.edit = false;
            this.openModal1 = false;
            for (var key in this.business ) {
               this.business[key] = null;
            }
            this.errors.clear();
        },
       fetchBusiness: async function(page_url)
        {
            let vm = this;
            page_url = page_url || 'api/businesses?q=' + vm.search
            try {
                 let response = await axios.get(page_url)
                 response.data.error ? vm.noResult = response.data.error : vm.noResult = '';
                 vm.businesses = response.data.data;
                 vm.makePagination(response.data.meta, response.data.links);
            }catch(err)
            {
                console.log(err)
            }
        },
        makePagination: function(meta, links)
        {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
            };
            this.pagination = pagination;
        },
       addBusiness: function()
        {
            if(this.edit == false)
            {
                let vm = this;
                axios.post('api/business', vm.business)
                .then(function (response){
                    alert("Business Inserted");
                    vm.business.businessName='';
                    vm.business.businessAddress='';
                    vm.business.businessType='';
                    vm.openModal1 = false;
                    vm.errors.clear();
                    vm.fetchBusiness();
                })
                .catch(function (error){
                   vm.errors.record(error.response.data);
                });
            }else
            {
                let vm = this;
                axios.put('api/business', vm.business)
                 .then(function (response){
                    alert("Business Updated");
                    vm.business.businessName='';
                    vm.business.businessAddress='';
                    vm.business.businessType='';
                    vm.openModal1 = false;
                    vm.errors.clear();
                    vm.fetchBusiness();
                })
                .catch(function (error) {
                    vm.errors.record(error.response.data);
                });
            }
          
        },
        editBusiness: function(id)
        {
            let vm = this;
            axios.get('api/business/' + id)
            .then(function (response) {
                vm.edit = true;
                vm.business = response.data.data;
                vm.business.business_id = response.data.data.id; 
                vm.openModal1 = true;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
         deleteBusiness: function(id)
        {
            let vm = this;
            axios.delete('api/business/'+id)
            .then(function (response){
                alert("Business Deleted");
                vm.fetchBusiness();
            })
            .catch(function (error) {
                console.log(error);
            });   
        },
    },
  }
</script>