class NotificationManager {
    constructor() {
        console.log("Notification Manager initialized");
        this.notificationList = document.getElementById("notificationList");
        this.notificationDot = document.getElementById("notificationDot");
        this.notificationCount = document.getElementById("notificationCount");
        this.toastElement = document.getElementById("notificationToast");
        this.initialize();
    }

    initialize() {
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        if (typeof window.Echo === "undefined") {
            console.error("Echo is not defined!");
            return;
        }

        this.setupEchoListeners();
        this.loadNotifications();
        this.setupEventListeners();
    }

    setupEchoListeners() {
        const userId = document.querySelector('meta[name="user-id"]')?.content;

        if (!userId) {
            console.warn("User ID not found");
            return;
        }

        try {
            window.Echo.channel(`user.${userId}`)
                .listen(".expense.notification", (data) => {
                    console.log("🔔 Notification received:", data);
                    this.handleNewNotification(data);
                })
                .error((error) => {
                    console.error("Echo error:", error);
                });
        } catch (error) {
            console.error("Failed to setup Echo listener:", error);
        }
    }

    loadNotifications() {
        fetch("/notifications/latest", {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                this.renderNotifications(data.notifications || []);
                this.updateUnreadCount(data.unread_count || 0);
            })
            .catch((error) => {
                console.error("Error loading notifications:", error);
            });
    }

    renderNotifications(notifications) {
        if (!this.notificationList) return;

        this.notificationList.innerHTML = "";

        if (notifications.length === 0) {
            this.notificationList.innerHTML = `
                <div class="text-center py-5">
                    <p class="text-muted mb-0">No notifications</p>
                </div>
            `;
            return;
        }

        notifications.forEach((notification) => {
            this.notificationList.appendChild(
                this.createNotificationElement(notification),
            );
        });
    }

    createNotificationElement(notification) {
        const div = document.createElement("div");
        div.className = `notification-item px-16 py-12 border-bottom ${notification.read ? "" : "bg-primary-50"}`;
        div.dataset.notificationId = notification.id;
        div.dataset.read = notification.read ? "true" : "false";
        div.style.cursor = "pointer";

        const time = notification.time || "Just now";

        div.innerHTML = `
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="flex-grow-1">
                    <p class="mb-1 text-sm text-secondary-light">${this.escapeHtml(notification.message)}</p>
                    <span class="text-xs text-muted">${time}</span>
                </div>
                ${!notification.read ? '<span class="badge bg-primary rounded-pill flex-shrink-0" style="font-size: 8px; margin-top: 2px;">New</span>' : ""}
            </div>
        `;

        div.addEventListener("click", () => {
            this.markAsRead(notification.id);
        });

        return div;
    }

    handleNewNotification(data) {
        this.addNotificationToTop(data);
        this.updateUnreadCount();
        this.showToast(data.message, data.type);
        this.playNotificationSound();
    }

    addNotificationToTop(notification) {
        if (!this.notificationList) return;

        const existing = this.notificationList.querySelector(
            `[data-notification-id="${notification.id}"]`,
        );
        if (existing) return;

        const item = this.createNotificationElement({
            ...notification,
            read: false,
        });

        this.notificationList.prepend(item);

        const items =
            this.notificationList.querySelectorAll(".notification-item");
        if (items.length > 50) {
            items[items.length - 1].remove();
        }

        this.updateEmptyState();
    }

    updateUnreadCount(count = null) {
        if (count === null) {
            fetch("/notifications/latest", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    this.updateUnreadCountUI(data.unread_count || 0);
                })
                .catch(() => {});
            return;
        }

        this.updateUnreadCountUI(count);
    }

    updateUnreadCountUI(count) {
        if (this.notificationDot) {
            this.notificationDot.style.display = count > 0 ? "block" : "none";
        }

        if (this.notificationCount) {
            if (count > 0) {
                this.notificationCount.style.display = "inline-block";
                this.notificationCount.textContent = count > 99 ? "99+" : count;
            } else {
                this.notificationCount.style.display = "none";
            }
        }

        if (count > 0) {
            document.title = `(${count}) Expense Management`;
        } else {
            document.title = "Expense Management";
        }
    }

    markAsRead(notificationId) {
        if (!notificationId) return;

        const item = this.notificationList?.querySelector(
            `[data-notification-id="${notificationId}"]`,
        );
        if (item && item.dataset.read === "true") return;

        fetch(`/notifications/${notificationId}/read`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    if (item) {
                        item.classList.remove("bg-primary-50");
                        item.dataset.read = "true";
                        const badge = item.querySelector(".badge");
                        if (badge) badge.remove();
                    }
                    this.updateUnreadCount();
                }
            })
            .catch((error) => {
                console.error("Error marking notification as read:", error);
            });
    }

    markAllAsRead() {
        fetch("/notifications/read-all", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    this.notificationList
                        ?.querySelectorAll(".notification-item")
                        .forEach((item) => {
                            item.classList.remove("bg-primary-50");
                            item.dataset.read = "true";
                            const badge = item.querySelector(".badge");
                            if (badge) badge.remove();
                        });
                    this.updateUnreadCount(0);
                    this.showToast(
                        "All notifications marked as read",
                        "success",
                    );
                }
            })
            .catch((error) => {
                console.error("Error marking all as read:", error);
            });
    }

    showToast(message, type = "info") {
        if (!this.toastElement) return;

        const toast = new bootstrap.Toast(this.toastElement, {
            autohide: true,
            delay: 5000,
        });

        const bgMap = {
            success: "bg-success text-white",
            error: "bg-danger text-white",
            warning: "bg-warning",
            info: "bg-info text-white",
        };

        const bgClass = bgMap[type] || bgMap.info;

        const toastMessage = document.getElementById("toastMessage");
        const toastTitle = document.getElementById("toastTitle");

        if (toastMessage) {
            toastMessage.textContent = message;
        }

        if (toastTitle) {
            toastTitle.textContent =
                type.charAt(0).toUpperCase() + type.slice(1);
        }

        this.toastElement.className = `toast align-items-center border-0 shadow-lg ${bgClass}`;
        this.toastElement.style.display = "block";
        this.toastElement.style.borderRadius = "8px";

        toast.show();

        this.toastElement.addEventListener("hidden.bs.toast", () => {
            this.toastElement.style.display = "none";
        });
    }

    playNotificationSound() {
        try {
            const audioContext = new (
                window.AudioContext || window.webkitAudioContext
            )();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = 800;
            oscillator.type = "sine";
            gainNode.gain.value = 0.3;

            oscillator.start();
            setTimeout(() => oscillator.stop(), 150);
        } catch (e) {
            // Silent fail
        }
    }

    escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    updateEmptyState() {
        if (!this.notificationList) return;
        const items =
            this.notificationList.querySelectorAll(".notification-item");
        if (items.length === 0) {
            this.notificationList.innerHTML = `
                <div class="text-center py-5">
                    <p class="text-muted mb-0">No notifications</p>
                </div>
            `;
        }
    }

    setupEventListeners() {
        document
            .getElementById("markAllReadBtn")
            ?.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.markAllAsRead();
            });

        const dropdownToggle = document.querySelector(
            '[data-bs-toggle="dropdown"]',
        );
        if (dropdownToggle) {
            dropdownToggle.addEventListener("shown.bs.dropdown", () => {
                this.loadNotifications();
            });
        }
    }
}

document.addEventListener("DOMContentLoaded", () => {
    window.notificationManager = new NotificationManager();
});

export default NotificationManager;
