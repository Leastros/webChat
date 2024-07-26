


<div x-on:resize.window="onWindowResize" x-data="messageApp()" class="rounded bg-body gap-2 m-2 d-flex flex-grow-1 border-0">

	<div x-show="!selectedMessage || !isSmallDevice" class="col overflow-auto flex-column">
		<nav>
			<ul class="m-2 navbar-nav d-flex gap-2 flex-row ms-auto">
				<a href="?page=messages&action=getMessages" class="btn ms-2">Inbox</a>
				<!--
				<button class="btn ms-2" :class="{ 'btn-primary': selectedTab == 'inbox'}" @click="selectTab('inbox')">
					Inbox
				</button>
				-->
				<button class="btn btn-primary" :class="{ 'btn-primary': selectedTab == 'sent' }" @click="selectTab('sent')">
					Sent Items
				</button>
				<button class="btn btn-primary" :class="{ 'btn-primary': selectedTab == 'new' }" @click="selectTab('new')">
					New Message
				</button>
			</ul>
		</nav>

		<!-- messages list -->
		<div class="list-group overflow-auto">

			<!-- message template -->
			<template x-for="msg in messages">
				<button class="p-3  border-end-0 border-start-0 rounded-0 d-flex  list-group-item list-group-item-action" @click="loadMessage(msg.id)">
					<img class="me-3 rounded-circle" width="50" height="50" src="resources/profile_picture.svg" style="min-width: 50px; width: 50px" />

					<div class="overflow-auto">
						<div class="d-flex justify-content-between">

							<h5 class="">
								John Doe <?php echo $info ?>
							</h5>
							<h6 class="">
								10. 12. 2022
							</h6>

						</div>

						<div class="ellipsis-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem quia neque qui est maiores, sapiente, voluptas, animi cupiditate corporis fuga officiis. Adipisci suscipit eum ullam, veritatis culpa eos tempore ad?</div>

					</div>
				</button>
			</template>
			<div class="list-group-item border-0"></div>

		</div>
	</div>

	<!-- message detail 
			 || !isSmallDevice 
	-->
	<div x-show="selectedMessage " class="card col m-2">
		<div class="card-header">
			<button class="btn btn-close" @click="closeMessage"></button>
		</div>

		<div class="card-body flex-grow-1">

			<!-- sender info -->
			<div class="mb-3">
				<img src="resources/profile_picture.svg" class="rounded-circle me-2" width="40" height="40" alt="sender image" />
				<strong>Sender Name</strong> - <span>John doe</span>
			</div>

			<!-- message content -->
			<div class="mb-3">
				<p class="card-text"></p>
			</div>

		</div>

		<!-- actions -->
		<div class="card-footer">
			<button class="btn btn-primary" @click="replyMessage">Reply</button>
			<button class="btn btn-danger ms-2" @click="deleteMessage">Delete</button>
		</div>
	</div>
</div>

<script>
	const small = 768;

	function messageApp() {
		return {
			selectedTab: 'inbox',
			selectedMessage: null,
			isSmallDevice: window.innerWidth < small,
			onWindowResize: function() {
				this.isSmallDevice = window.innerWidth < small;
			},
			messages: [{
					id: 1,
				},
				{
					id: 2,
				},
			],
			loadMessages() {
				fetch('?page=messages&action=getMessages', {
						method: "POST",
					})
					.then((response) => {
						return response.json().then((data) => {
							console.log(data);
							this.messages = data;
						}).catch((err) => {
							console.log(err);
						})
					});
			},
			loadMessage(msgId) {
				this.selectedMessage = true;
				return;

				fetch('?page=messages&action=getMessage', {
						method: "POST",
						body: JSON.stringify({
							msgId: msgId
						})
					})
					.then((response) => {
						return response.json().then((data) => {
							console.log(data);
							this.messages = data;
						}).catch((err) => {
							console.log(err);
						})
					});
			},
			closeMessage() {
				this.selectedMessage = null;
			},
			deleteMessage() {

			},
			replyMessage() {

			},
			selectTab(tabname) {
				this.selectedTab = tabname;
				loadMessages();
			},
			init() {},
		};
	}
</script>
