<template>
	<v-main >
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
			<Breadcrumbs/>
			<v-row justify="center">
				<v-col sm="12" md="12" lg="12">
					<v-data-table color="white" :headers="headers" :items="dataList" :search="search" class="elevation-4">
					
						<template v-slot:item.type="{ item }">
							{{item.type.name}}
						</template>
						  <template v-slot:item.status="{ item }">
                                    <v-switch v-model="item.status" @change="changeStatus(item)" color="primary" inset></v-switch>
                                </template>
							<template v-slot:item.action="{ item }">
							  <v-btn small @click="detailItem(item)" :disabled="$store.state.authUser.type == 1 && item.type.id==1?true:false">Permission</v-btn>
						</template>
						<template v-slot:no-data>
							<NoDataList :loading="loading" @initialize="initialize"></NoDataList>
						</template>
					</v-data-table>
					<div class="text-center">
                                <v-pagination
                                v-model="filters.page"
                                :length="pageCount"
                                @input="getUser"
                                ></v-pagination>
                            </div>
				</v-col>
			</v-row>
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
import Breadcrumbs from "./../../common/Breadcrumbs"
import ToolbarLeft from "./../../common/ToolbarLeft"
import NoDataList from "./../../common/NoDataList"
export default {
	components:{
		Breadcrumbs,
		ToolbarLeft,
		NoDataList,
	},
	data: () => ({
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
		userType:[],
	 filters:
        {
			page: 1,
			show: 20,
        },
		
		headers: [
			{ text: "ID", align: "left", value: "id" },
			{ text: "Name", value: "name" },
			{ text: "Email", value: "email" },
			{ text: "Type", value: "type" },
			{ text: "Status", value: "status" },
			{ text: "Action", value: "action" }
		],
		emailRules: [
			v => !!v || "E-mail is required",
			v => /.+@.+.\.+.+/.test(v) || "E-mail must be valid"
		],
		usernameRules: [
			v => !!v || "Name is required",
			v => (v || "").indexOf(" ") < 0 || "No spaces are allowed"
		],
		passwordRules: [
			v => (v || "").length >= 8 || `A minimum of 8 characters is allowed`
		],
		editedIndex: -1,
		editedItem: {
			name: "",
			email: "",
			type: '',
			mobile: "",
			address: "",
			status: 1
		},
		defaultItem: {
			name: "",
			email: "",
			type: '',
			mobile: "",
			address: "",
			status: 1
		},
		
	}),

	props: 
	{
		source: String
	},

	computed: 
	{
		formTitle() 
		{
			return this.editedIndex === -1 ? "New Item" : "Edit Item";
		}
	},
	watch: {},

	created() 
	{
		this.initialize();
		this.dataBreadcrumbs=this.$route.matched;
	},

	methods: {
		async initialize() 
		{
			 try 
			 {
        		let { data } = await axios({
          		method: "get",
          		url: "/app/usertype"
        		});
        		this.userType = data;
			} 
			catch (e) 
			{

			}
			this.getUser();
		},

		async getUser()
		{
			this.start();
			try 
			{
				let { data } = await axios({
					method: "get",
					url: "/app/user",
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

async changeStatus(i)
		{
			this.loading = true;
			try {
				let { data } = await axios({
					method: "get",
					url: "/app/user/"+i.id+"/edit",
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
		   detailItem(item)
        {
            this.$router.push('/privilige/permission/'+item.id);

        },
		
		
		
		
	
	
	}
};
</script>