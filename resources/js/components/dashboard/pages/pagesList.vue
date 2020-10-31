<template>
  <v-main>
    <v-container fluid>
     <v-overlay :value="showFullLoading" :absolute="absolute">
				<v-progress-circular indeterminate size="64"></v-progress-circular>
			</v-overlay>
        <v-row >
						<v-col sm="6" md="3" lg="3">
							<v-menu
								v-model="date1"
								:close-on-content-click="false"
								:nudge-right="40"
								transition="scale-transition"
								offset-y
								min-width="290px"
							>
								<template v-slot:activator="{ on }">
								<v-text-field
									v-model="filters.start"
									label="Start Date"
									readonly
									prepend-inner-icon="event"
									v-on="on"
									filled
								></v-text-field>
								</template>
								<v-date-picker @change="getPages" v-model="filters.start" @input="date1 = false"></v-date-picker>
							</v-menu>
						</v-col>
						<v-col sm="6" md="3" lg="3">
							<v-menu
								v-model="date2"
								:close-on-content-click="false"
								:nudge-right="40"
								transition="scale-transition"
								offset-y
								min-width="290px"
							>
								<template v-slot:activator="{ on }">
								<v-text-field
									v-model="filters.end"
									label="End Date"
									prepend-inner-icon="event"
									readonly
									v-on="on"
									filled
								></v-text-field>
								</template>
								<v-date-picker @change="getPages" v-model="filters.end" @input="date2 = false"></v-date-picker>
							</v-menu>
						</v-col>
                        <v-col cols="3">
                            <v-select
                                v-model="filters.category_id"
                                :items="dataCategory"
                                item-text="name"
                                item-value="id"
                                label="Categories"
                                required
							                	clearable
                                filled
								                @change="getPages"
                            ></v-select>                            
                        </v-col>
                        <v-col cols="3">
                            <v-select
                                v-model="filters.sortflag"
                                :items="dataSort"
                                item-text="text"
                                item-value="value"
                                label="Sorting Data"
                                required
                                filled
								                @change="getbySort"
                            ></v-select>                            
                        </v-col>
                    </v-row>
      <ToolbarLeft>
       <v-spacer></v-spacer>
       
				<v-text-field
					v-model="search"
					append-icon="search"
					label="Search"
					hide-details
					outlined
					dense
				></v-text-field>
      </ToolbarLeft>
      <Breadcrumbs />
      <v-row justify="center">
        <v-col sm="12" md="12" lg="12">
     	<v-data-table color="white" :headers="headers" :items="dataList" :search="search" class="elevation-4">
            <template v-slot:item.image="{ item }">
              <v-img
                :src="item.image ? item.image : '/no_image.png'"
                lazy-src="no_image.png"
                aspect-ratio="1"
                class="grey lighten-2"
                width="80"
                height="80"
              ></v-img>
            </template>
            <template v-slot:item.user_id="{ item }">
            {{ item.user.name }}
            </template>
            <template v-slot:item.category_id="{ item }">
            {{ item.category.name }}
            </template>
            	<template v-slot:item.body="{ item }">
                <p  v-html="item.body  && item.body.length>70?item.body.slice(0,68)+'...':item.body"></p>
				</template>
           <template v-slot:item.status="{ item }">
            <v-switch v-model="item.status" @change="changeStatus(item)" color="primary" inset></v-switch>
            </template>
            <template v-slot:item.action="{ item }">
              <v-icon color="blue" small @click="editItem(item)">edit</v-icon>
              <v-icon color="red" small @click="deleteItem(item)">delete</v-icon>
            </template>
            <template v-slot:no-data>
              <NoDataList :loading="loading" @initialize="initialize"></NoDataList>
            </template>
          </v-data-table>
          <div class="text-center">
                                <v-pagination
                                v-model="filters.page"
                                :length="pageCount"
                                @input="getPages"
                                ></v-pagination>
                            </div>
        </v-col>
      </v-row>
      <DeleteModal :trigger="isDelete" :title="deleteTitle" :body="deleteBody" @request="remove"></DeleteModal>
      <v-btn bottom color="primary" dark fab fixed right @click="add">
        <v-icon>add</v-icon>
      </v-btn>

    </v-container>
    <v-snackbar
      v-model="snackbar"
      :vertical="snackvertical"
      :timeout="snacktimeout"
      :top="snacktop"
      :right="snackright"
      :color="snackcolor"
    >
      {{ snacktext }}
      <v-btn color="white" text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </v-main>
</template>

<script>
import DeleteModal from "./../../common/DeleteModal";
import NoDataList from "./../../common/NoDataList";
import ImageModule from "./../../common/ImageModule";
import Breadcrumbs from "./../../common/Breadcrumbs";
import ToolbarLeft from "./../../common/ToolbarLeft";

export default {
  components: {
    DeleteModal,
    NoDataList,
    ImageModule,
    ToolbarLeft,
    Breadcrumbs,
  },
  data: () => ({
    isImage: false,
    search: "",
    absolute: true,
  	date1:false,
		date2:false,
    loading: false,
    edit: true,
    dialog: false,
    dataList: [],
    dataCategory:[],
    dataIndex: null,
		deleteTitle: "",
		deleteBody: "",
		isDelete: false,
        itemsPerPage:1,
        pageCount:2,
   editorConfig: {
            toolbar: [ [  ] ]
        },
    headers: [
      { text: "Image", align: "left", value: "image" , width:30},
	  { text: "Title", value: "title" },
      { text: "Body", value: "body" },
      { text: "Author", value: "user_id"  },
      { text: "Category", value: "category_id" },
      { text: "Status", value: "status" , width:30 },
      { text: "Action", value: "action" , width:30 },
    ],
    editedIndex: -1,
    editedItem: {
      title: "",
      image: "",
      body:'',
      status: 1,
      category_id:"",
    },
    dataIndex: null,
    deleteTitle: "",
    deleteBody: "",
    isDelete: false,
    defaultItem: {
    title: "",
      image: "",
      body:'',
      status: 1,
      category_id:"",
    },

    dataSort:[

      { text: "Sort By New Data", value: "Sort By New Data" },
      { text: "Sort By Old Data", value: "Sort By Old Data" },
      { text: "Sort By Title ASC", value: "Sort By Title ASC" },
      { text: "Sort By Title DESC", value: "Sort By Title DESC" },
    ],

  
    filters:
        {
			page: 1,
      show: 20,
      sort_value:'id',
      sort_type:'DESC',
      start:new Date().toISOString().substr(0, 10),
      end:new Date().toISOString().substr(0, 10),
      sortflag:'',
        },
  }),

  props: 
  {
    source: String,
  },

  computed: 
  {
    formTitle() 
    {
      return this.editedIndex === -1 ? "New Page" : "Edit Page";
    },
  },

  watch: {},

  created() 
  {
    this.initialize();
  },

  methods: 
  {
      
    cancel() 
    {
      this.isImage = !this.isImage;
    },

    receiveImage(item) 
    {
      this.isImage = !this.isImage;
      this.editedItem.image = item.src;
    },

getbySort()
{
  if(this.filters.sortflag == "Sort By New Data")
  {
     this.filters.sort_value='id';
    this.filters.sort_type='ASC';
  }
  else if(this.filters.sortflag == "Sort By Old Data")
  {
     this.filters.sort_value='id';
     this.filters.sort_type='DESC';
  }
   else if(this.filters.sortflag == "Sort By Title ASC")
  {
     this.filters.sort_value='title';
      this.filters.sort_type='ASC';
  }
  else if(this.filters.sortflag == "Sort By Title DESC")
  {
     this.filters.sort_value='title';
    this.filters.sort_type='DESC';
  }              
                   
    this.getPages();
},
    async initialize() 
    {
        try 
			 {
        		let { data } = await axios({
          		method: "get",
          		url: "/app/category"
        		});
        		this.dataCategory = data;
			} 
			catch (e) 
			{

			}
      this.getPages();
    },

    async getPages()
		{
			this.start();
			try 
			{
				let { data } = await axios({
					method: "get",
					url: "/app/pages",
					 params: this.filters
				});
				this.dataList = data.data;
                this.itemsPerPage=data.per_page;
                this.pageCount=data.last_page;
                this.filters.page=data.current_page
				
			} 
			catch (e) 
			{
				this.fail();
			}
			finally
			{
				this.finish();
			}
		},

add()
{
 this.$router.push('/pages/add');
},

editItem(item)
{
  this.$router.push('/pages/edit/'+item.id);
},
async changeStatus(i)
		{
			this.loading = true;
			try {
				let { data } = await axios({
					method: "get",
					url: "/app/pages/"+i.id+"/edit",
					params:{
						status: i.status
					},

				});
				if (data.status) 
				{
					this.snacks("Successfully Changed", "green");
					this.close();
				} 
				else 
				{
					this.snacks("Failed", "red");
					this.loading = false;
				}
			} 
			catch (e) 
			{
				this.snacks("Error, Server issue", "red");
				this.loading = false;
			}


		},


    deleteItem(item) 
    {
      this.dataIndex = this.dataList.indexOf(item);
      this.deleteTitle = "Are you sure you want to delete this item?";
      this.isDelete = !this.isDelete;
    },

    close() 
    {
      this.dialog = false;
      this.loading = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    async save() 
    {
      this.loading = true;
      if (this.editedIndex > -1) 
      {
        try 
        {
          let { data } = await axios({
            method: "put",
            url: "/app/pages/" + this.editedItem.id,
            data: this.editedItem,
          });
          if (data.status) 
          {
            this.snacks("Successfully Added", "green");
            Object.assign(this.dataList[this.editedIndex], this.editedItem);
            this.close();
          } 
          else 
          {
            this.snacks("Failed", "red");
            this.loading = false;
          }
        } 
        catch (e) 
        {
          this.snacks("Failed", "red");
          this.loading = false;
        }
      } 
      else 
      {
        try 
        {
          
          let { data } = await axios({
            method: "post",
            url: "/app/pages",
            data: this.editedItem,
          });
          console.log(data);
          if (data.status) 
          {
            this.dataList.unshift(data.data);
            this.snacks("Successfully Added", "green");
            this.close();
          } 
          else 
          {
            this.snacks("Failed", "red");
            this.loading = false;
          }
        } 
        catch (e) 
        {
          this.snacks("Failed", "red");
          this.loading = false;
        }
      }
    },
    async remove() {
      this.loading = true;
      try {
        let { data } = await axios({
          method: "delete",
          url: "/app/pages/" + this.dataList[this.dataIndex].id,
        });
        if (data.status) {
          this.snacks("Successfully Done", "green");
          this.dataList.splice(this.dataIndex, 1);
          this.close();
        } else {
          this.snacks(data.data, "red");
          this.loading = false;
        }
      } catch (e) {
        this.snacks("Operation Failed", "red");
        this.loading = false;
      }
    },
  },
};
</script>
