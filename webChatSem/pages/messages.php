<?php

include "controllers/MessageController.php";

$info = "Nic";

$id = $_SESSION['user_id'];

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    if ($filter === "inbox")
        $messages = MessageController::getAll($id, getFilter::Recipient);
    else if ($filter === "sent")
        $messages = MessageController::getAll($id, getFilter::Sender);
} 
else {
    $messages = MessageController::getAll($id, getFilter::SenderAndRecipient);
}


if (isset($_POST['sendMessage'])) {

    if (isset($_POST['recipientInput']) && isset($_POST['messageInput'])) {
        $newMessage = new MessageModel();
        $newMessage->sender = ProfileController::get($id);
        $newMessage->recipient = ProfileController::find($_POST['recipientInput']);
        $newMessage->content = $_POST['messageInput'];
        $newMessage->timestamp = new DateTime();

		if ($newMessage->validate()) {
			MessageController::add($newMessage);
		}


        header("Refresh:0");
    }

}

if (isset($_GET['getMessage'])) {

}

if (isset($_GET['action'])) {


	$action = $_GET['action'];
	if ($action === "getMessages") {
		
	}
}


?>


<div x-on:resize.window="onWindowResize" x-data="messageApp()" class="rounded bg-body gap-2 m-2 d-flex flex-grow-1 border-0">

	<div x-show="!selectedMessage || !isSmallDevice" class="col overflow-auto flex-column">
		<nav>
			<ul class="m-2 navbar-nav d-flex gap-2 flex-row ms-auto">
            <a href="?page=messages" class="btn ms-2">All</a>
				<a href="?page=messages&filter=inbox" class="btn ms-2">Inbox</a>
                <a href="?page=messages&filter=sent" class="btn ms-2">Sent</a>
				<!--
				<button class="btn ms-2" :class="{ 'btn-primary': selectedTab == 'inbox'}" @click="selectTab('inbox')">
					Inbox
				</button>
				<button class="btn btn-primary" :class="{ 'btn-primary': selectedTab == 'sent' }" @click="selectTab('sent')">
                    Sent Items
				</button>
				<button class="btn btn-primary" :class="{ 'btn-primary': selectedTab == 'new' }" @click="selectTab('new')">
                    New Message
				</button>
                -->
			</ul>
		</nav>

		<!-- messages list -->
		<div class="list-group overflow-auto">
            <?php foreach($messages as $message) { ?>
                <?php
                    echo ""
                ?>
                <a href="?page=messages&getMessage=<?php echo $message->id ?>" class="p-3  border-end-0 border-start-0 rounded-0 d-flex  list-group-item list-group-item-action">
                    <?php $imageLink = $message->sender->imageLink ?? "resources/profile_picture.svg"  ?>
                    <img class="me-3 rounded-circle" width="50" height="50" src="<?php echo $imageLink ?>" style="min-width: 50px; width: 50px" />

                    <div class="overflow-auto">
                        <div class="d-flex justify-content-between">

                            <h5 class="">
                                <?php echo $message->sender->firstName . " " . $message->sender->lastName;  ?>
                            </h5>
                            <h6 class="">
                                <?php echo "(" . $message->sender->username . ")";  ?>
                            </h6>
                            <h6 class="">
                                <?php echo $message->timestamp; ?>
                            </h6>

                        </div>

                        <div class="ellipsis-text"><?php echo $message->content;  ?></div>

                    </div>
            </a>
            <?php } ?>
		</div>
	</div>

	<!-- message detail 
			 || !isSmallDevice 
	-->
    <form method="post" action="" class="card col m-2">
        <div class="card-header">
            <button class="btn btn-close" @click="closeMessage"></button>
        </div>

        <div class="card-body flex-grow-1">

            <!-- sender info -->
            <div class="mb-3">
                <div>
                    <label for="recipientInput" class="form-label">Recipiant username</label>
                    <input id="recipientInput" name="recipientInput" type="text" class="form-control" />
                </div>
            </div>

            <!-- message content -->
            <div class="mb-3">
                <div>
                    <label for="messageInput" class="form-label">Message</label>
                    <input id="messageInput" name="messageInput" type="text" class="form-control" />
                </div>
            </div>

        </div>

        <!-- actions -->
        <div class="card-footer">
            <input type="submit" name="sendMessage" id="sendMessage" value="Send" class="btn btn-primary" @click="replyMessage" />
        </div>
    </form>
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
