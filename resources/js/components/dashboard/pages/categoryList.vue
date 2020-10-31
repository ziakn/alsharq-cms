<template>
  <v-main>
    <v-container fluid>
     <v-overlay :value="showFullLoading" :absolute="absolute">
				<v-progress-circular indeterminate size="64"></v-progress-circular>
			</v-overlay>
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
            <template v-slot:item.parent_id="{ item }">
              <p v-if="item.parent_id > 0">{{ item.parent.name }}</p>
              <p v-else>No Parent</p>
            </template>
            <template v-slot:item.status="{ item }">
              <v-chip :color="item.status ? 'green' : 'red'" text-color="white">
                {{ item.status ? "Active" : "Disable"  }}
              </v-chip>
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
                                @input="getCategory"
                                ></v-pagination>
                            </div>
        </v-col>
      </v-row>
      <DeleteModal :trigger="isDelete" :title="deleteTitle" :body="deleteBody" @request="remove"></DeleteModal>
      <v-btn bottom color="primary" dark fab fixed right @click="dialog = !dialog">
        <v-icon>add</v-icon>
      </v-btn>
  <v-dialog v-model="dialog" max-width="500px" persistent>
							<v-card>
							<ImageModule :toggle="isImage" @send="receiveImage" @cancel="cancel"/>
								<v-card-title>
									<span class="headline">{{ formTitle }}</span>
								</v-card-title>

								<v-card-text>
									<v-container grid-list-md>
										<v-layout wrap>
											<v-col cols="12">
												<v-text-field
													:rules="[v => !!v || 'Name is required']"
													v-model="editedItem.name"
													label="Name*"
													filled
												></v-text-field>
											</v-col>
											<v-col cols="12">
												<v-textarea 
                                                    v-model="editedItem.description" 
                                                    label="Description"
                                                    filled
                                                ></v-textarea>
											</v-col>
											<v-col cols="12">
												<v-card
													class="mx-auto"
													width="180"
													outlined
													align="center"
													justify="center"
												>
												<v-img
												:src="editedItem.image?editedItem.image:'/images/plus.png'"
												aspect-ratio="1"
												@click="isImage=!isImage"
												>
												</v-img>
												<v-card-subtitle v-if="!editedItem.image">Add image</v-card-subtitle>
												<v-card-text v-else class="my-2">
													<v-btn x-small color="primary" @click="isImage=!isImage">
														Change
													</v-btn>
													<v-btn x-small color="primary" @click="editedItem.image=''">
														Remove
													</v-btn>
												</v-card-text>
												
												</v-card>
											</v-col>
											<v-col cols="12">
												<v-select
													v-model="editedItem.status"
													:items="dataStatus"
													item-text="name"
													item-value="value"
													:rules="[v => !!v || 'Status is required']"
													label="Status"
													required
													filled
												></v-select>
											</v-col>
										</v-layout>
									</v-container>
								</v-card-text>

								<v-card-actions>
									<v-spacer></v-spacer>
									<v-btn color="red darken-1" text @click="close">Cancel</v-btn>
									<v-btn
										color="primary"
										:loading="loading"
										:disabled="loading"
										text
										@click="save"
									>Save</v-btn>
								</v-card-actions>
							</v-card>
						</v-dialog>
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
   
    loading: false,
    edit: true,
    dialog: false,
    dataList: [],
    dataIndex: null,
		deleteTitle: "",
		deleteBody: "",
		isDelete: false,
        itemsPerPage:1,
		pageCount:2,
    headers: [
      { text: "ID", align: "left", value: "id" },
      { text: "Image", value: "image" },
	  { text: "Name", value: "name" },
	    { text: "Description", value: "description" },
      { text: "Status", value: "status" },
      { text: "Action", value: "action" },
    ],
    editedIndex: -1,
    editedItem: {
      id: 0,
      name: "",
      image: "",
      status: 1,
    },
    dataIndex: null,
    deleteTitle: "",
    deleteBody: "",
    isDelete: false,
    defaultItem: {
      id: 0,
      name: "",
      image: "",
      status: 1,
    },
    dataStatus: 
    [
      { name: "Active", value: 1 },
      { name: "Disable", value: 0 },
    ],
    filters:
        {
			page: 1,
			show: 20,
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
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
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

    async initialize() 
    {
      this.getCategory();
    },

    async getCategory()
		{
			this.start();
			try 
			{
				let { data } = await axios({
					method: "get",
					url: "/app/category",
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

    editItem(item) 
    {
      this.edit = false;
      this.editedIndex = this.dataList.findIndex((i) => i.id == item.id);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
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
            url: "/app/category/" + this.editedItem.id,
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
            url: "/app/category",
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
    async remove() 
    {
      this.loading = true;
      try 
      {
        let { data } = await axios({
          method: "delete",
          url: "/app/category/" + this.dataList[this.dataIndex].id,
        });
        if (data.status) 
        {
          this.snacks("Successfully Done", "green");
          this.dataList.splice(this.dataIndex, 1);
          this.close();
        } 
        else 
        {
          this.snacks(data.data, "red");
          this.loading = false;
        }
      } catch (e) 
      {
        this.snacks("Operation Failed", "red");
        this.loading = false;
      }
    },
  },
};
</script>
