{{-- resources/views/student/simulations/cashier.blade.php --}}
<x-layouts :title="'Cashier Simulation - ' . $activity->name">
<div class="flex-1 h-screen bg-gray-100">
    <div class="h-full flex flex-col">
        {{-- Top Navigation --}}
        <div class="bg-white border-b px-6 py-3 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Cashier Simulation</h1>
                <p class="text-sm text-gray-600">{{ $activity->name }} - {{ $section->class_name }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm text-gray-600">Session Time</div>
                    <div id="sessionTimer" class="text-lg font-semibold">00:00</div>
                </div>
                <button onclick="exitSimulation()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Exit
                </button>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 flex overflow-hidden">
            {{-- Left Panel - Menu --}}
            <div class="w-2/3 p-6 overflow-y-auto">
                {{-- Statistics Cards --}}
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-gray-600 text-sm">Orders Processed</div>
                        <div id="totalOrders" class="text-3xl font-bold text-blue-600">0</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-gray-600 text-sm">Total Revenue</div>
                        <div id="totalRevenue" class="text-3xl font-bold text-green-600">₱0.00</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-gray-600 text-sm">Actions Logged</div>
                        <div id="actionsCount" class="text-3xl font-bold text-purple-600">0</div>
                    </div>
                </div>

                {{-- Category Filters --}}
                <div class="bg-white rounded-lg shadow p-4 mb-4">
                    <div class="flex gap-2 flex-wrap" id="categoryFilters">
                        <button onclick="filterCategory('All', event)" class="category-btn active px-4 py-2 rounded bg-blue-600 text-white">
                            All
                        </button>
                        <button onclick="filterCategory('Main', event)" class="category-btn px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">
                            Main Course
                        </button>
                        <button onclick="filterCategory('Appetizer', event)" class="category-btn px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">
                            Appetizers
                        </button>
                        <button onclick="filterCategory('Beverage', event)" class="category-btn px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">
                            Beverages
                        </button>
                        <button onclick="filterCategory('Dessert', event)" class="category-btn px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">
                            Desserts
                        </button>
                    </div>
                </div>

                {{-- Menu Items Grid --}}
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="font-semibold mb-4 text-lg">Menu Items</h3>
                    <div id="menuGrid" class="grid grid-cols-3 gap-3">
                        {{-- Menu items will be rendered here by JavaScript --}}
                    </div>
                </div>
            </div>

            {{-- Right Panel - Current Order --}}
            <div class="w-1/3 bg-white p-6 shadow-lg flex flex-col">
                <h3 class="text-xl font-bold mb-4">Current Order</h3>

                {{-- Order Items --}}
                <div class="flex-1 overflow-y-auto mb-4" id="orderItems">
                    <div class="text-gray-400 text-center py-8" id="emptyOrderMessage">
                        No items added
                    </div>
                </div>

                {{-- Discount Selection --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Discount</label>
                    <select id="discountType" class="w-full border rounded p-2">
                        <option value="none">No Discount</option>
                        <option value="pwd">PWD (20%)</option>
                        <option value="senior">Senior Citizen (20%)</option>
                        <option value="manager">Manager Discount (15%)</option>
                    </select>
                </div>

                {{-- Order Totals --}}
                <div class="border-t pt-4 space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span id="subtotal">₱0.00</span>
                    </div>
                    <div class="flex justify-between text-red-600" id="discountRow" style="display: none;">
                        <span>Discount:</span>
                        <span id="discount">-₱0.00</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold border-t pt-2">
                        <span>Total:</span>
                        <span id="total">₱0.00</span>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Payment Method</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="setPaymentMethod('cash')" id="cashBtn" class="payment-btn p-2 rounded border bg-blue-600 text-white">
                            Cash
                        </button>
                        <button onclick="setPaymentMethod('card')" id="cardBtn" class="payment-btn p-2 rounded border bg-white hover:bg-gray-50">
                            Card
                        </button>
                    </div>
                </div>

                {{-- Cash Input --}}
                <div class="mb-4" id="cashInputDiv" style="display: block;">
                    <label class="block text-sm font-medium mb-2">Cash Received</label>
                    <input type="number" id="cashReceived" placeholder="Enter amount"
                           class="w-full border rounded p-2" step="0.01" min="0">
                    <div id="changeDisplay" class="mt-2 text-green-600 font-medium" style="display: none;">
                        Change: <span id="changeAmount">₱0.00</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-2">
                    <button onclick="processPayment()" id="processPaymentBtn"
                            class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Process Payment
                    </button>

                    <button onclick="submitSimulation()" id="submitSimulationBtn"
                            class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Submit Simulation
                    </button>
                </div>

                <div class="mt-4 text-xs text-gray-500 text-center">
                    Session ID: <span id="sessionIdDisplay">{{ $session ? $session->id : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Receipt Modal --}}
<div id="receiptModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            {{-- Receipt Header --}}
            <div class="text-center border-b-2 border-dashed pb-4 mb-4">
                <h2 class="text-2xl font-bold">RECEIPT</h2>
                <p class="text-sm text-gray-600">Thank You for Your Order!</p>
                <div class="mt-2 text-xs text-gray-500">
                    <div>Order #<span id="receiptOrderNum">0</span></div>
                    <div id="receiptDateTime"></div>
                </div>
            </div>

            {{-- Receipt Items --}}
            <div id="receiptItems" class="mb-4">
                <!-- Items will be inserted here -->
            </div>

            {{-- Receipt Totals --}}
            <div class="border-t-2 border-dashed pt-3 space-y-2">
                <div class="flex justify-between text-sm">
                    <span>Subtotal:</span>
                    <span id="receiptSubtotal">₱0.00</span>
                </div>
                <div id="receiptDiscountRow" class="flex justify-between text-sm text-red-600" style="display: none;">
                    <span>Discount (<span id="receiptDiscountType"></span>):</span>
                    <span id="receiptDiscount">-₱0.00</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-2">
                    <span>TOTAL:</span>
                    <span id="receiptTotal">₱0.00</span>
                </div>
            </div>

            {{-- Payment Details --}}
            <div class="border-t-2 border-dashed pt-3 mt-3 space-y-1 text-sm">
                <div class="flex justify-between">
                    <span>Payment Method:</span>
                    <span id="receiptPaymentMethod" class="font-semibold uppercase">CASH</span>
                </div>
                <div id="receiptCashDetails" style="display: none;">
                    <div class="flex justify-between">
                        <span>Cash Received:</span>
                        <span id="receiptCashReceived">₱0.00</span>
                    </div>
                    <div class="flex justify-between font-semibold text-green-600">
                        <span>Change:</span>
                        <span id="receiptChange">₱0.00</span>
                    </div>
                </div>
            </div>

            {{-- Receipt Footer --}}
            <div class="text-center mt-6 pt-4 border-t-2 border-dashed">
                <p class="text-xs text-gray-600">Cashier Simulation System</p>
                <p class="text-xs text-gray-500">Session ID: <span id="receiptSessionId"></span></p>
                <p class="text-xs text-gray-400 mt-2">This is a simulated receipt</p>
            </div>

            {{-- Close Button --}}
            <div class="mt-6">
                <button onclick="closeReceipt()" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Close Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.category-btn.active {
    background-color: #2563eb !important;
    color: white !important;
}

.menu-item {
    transition: transform 0.2s;
}

.menu-item:hover {
    transform: translateY(-2px);
}

.menu-item:active {
    transform: translateY(0);
}
</style>

<script>
// Configuration
const CSRF_TOKEN = '{{ csrf_token() }}';
const SESSION_ID = {{ $session ? $session->id : 'null' }};
const ACTIVITY_ID = {{ $activity->activity_id }};
const SECTION_ID = {{ $section->section_id }};

// Menu data
const menuItems = [
    { id: 1, name: 'Classic Burger', price: 149.00, category: 'Main' },
    { id: 2, name: 'Cheese Pizza', price: 199.00, category: 'Main' },
    { id: 3, name: 'Grilled Chicken', price: 179.00, category: 'Main' },
    { id: 4, name: 'Pasta Carbonara', price: 169.00, category: 'Main' },
    { id: 5, name: 'Caesar Salad', price: 89.00, category: 'Appetizer' },
    { id: 6, name: 'French Fries', price: 59.00, category: 'Appetizer' },
    { id: 7, name: 'Onion Rings', price: 69.00, category: 'Appetizer' },
    { id: 8, name: 'Iced Coffee', price: 79.00, category: 'Beverage' },
    { id: 9, name: 'Fresh Juice', price: 89.00, category: 'Beverage' },
    { id: 10, name: 'Soft Drink', price: 49.00, category: 'Beverage' },
    { id: 11, name: 'Chocolate Cake', price: 99.00, category: 'Dessert' },
    { id: 12, name: 'Ice Cream', price: 79.00, category: 'Dessert' }
];

// State
let currentOrder = [];
let currentCategory = 'All';
let paymentMethod = 'cash';
let totalOrders = 0;
let totalRevenue = 0;
let actionsCount = 0;
let sessionStartTime = Date.now();
let sessionId = SESSION_ID;
let isSubmitting = false;

// Initialize
document.addEventListener('DOMContentLoaded', async () => {
    console.log('Cashier Simulation Initialized');
    console.log('Activity ID:', ACTIVITY_ID);
    console.log('Section ID:', SECTION_ID);
    console.log('Session ID:', sessionId);

    // Start session if not exists
    if (!sessionId) {
        await startSession();
    }

    renderMenu();
    startTimer();

    // Event listeners
    const discountTypeEl = document.getElementById('discountType');
    const cashReceivedEl = document.getElementById('cashReceived');

    if (discountTypeEl) {
        discountTypeEl.addEventListener('change', (e) => {
            logAction('discount_applied', { type: e.target.value });
            updateTotals();
        });
    }

    if (cashReceivedEl) {
        cashReceivedEl.addEventListener('input', updateChange);
    }
});

// Start simulation session
async function startSession() {
    try {
        const response = await fetch('{{ route("simulation.start") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                activity_id: ACTIVITY_ID,
                role_name: 'Cashier'
            })
        });

        const data = await response.json();
        console.log('Start session response:', data);

        if (data.success) {
            sessionId = data.session_id;
            const sessionDisplay = document.getElementById('sessionIdDisplay');
            if (sessionDisplay) {
                sessionDisplay.textContent = sessionId;
            }
            console.log('Session started successfully:', sessionId);
        } else {
            throw new Error(data.message || 'Failed to start session');
        }
    } catch (error) {
        console.error('Failed to start session:', error);
        alert('Failed to start simulation session. Please refresh and try again.');
    }
}

// Log action to backend
async function logAction(actionType, actionData) {
    if (!sessionId) {
        console.warn('No session ID, skipping action log');
        return;
    }

    actionsCount++;
    const actionsCountEl = document.getElementById('actionsCount');
    if (actionsCountEl) {
        actionsCountEl.textContent = actionsCount;
    }

    try {
        await fetch('{{ route("simulation.log") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                session_id: sessionId,
                action_type: actionType,
                action_data: actionData,
                timestamp: new Date().toISOString()
            })
        });
    } catch (error) {
        console.error('Failed to log action:', error);
    }
}

// Render menu items
function renderMenu() {
    const grid = document.getElementById('menuGrid');
    if (!grid) return;

    const filtered = currentCategory === 'All'
        ? menuItems
        : menuItems.filter(item => item.category === currentCategory);

    grid.innerHTML = filtered.map(item => `
        <button onclick="addToOrder(${item.id})"
                class="menu-item bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-md">
            <div class="text-lg font-semibold">${item.name}</div>
            <div class="text-sm opacity-90">₱${item.price.toFixed(2)}</div>
        </button>
    `).join('');
}

// Filter by category - FIXED: Added event parameter
function filterCategory(category, event) {
    currentCategory = category;

    // Update button styles
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });

    // Use event.target safely
    if (event && event.target) {
        event.target.classList.add('active', 'bg-blue-600', 'text-white');
        event.target.classList.remove('bg-gray-200', 'text-gray-700');
    }

    renderMenu();
    logAction('category_filtered', { category });
}

// Add item to order
function addToOrder(itemId) {
    const item = menuItems.find(i => i.id === itemId);
    if (!item) return;

    const existingItem = currentOrder.find(i => i.id === itemId);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        currentOrder.push({ ...item, quantity: 1 });
    }

    logAction('item_added', {
        item: item.name,
        price: item.price,
        category: item.category
    });

    renderOrder();
    updateTotals();
}

// Remove item from order
function removeFromOrder(itemId) {
    const item = currentOrder.find(i => i.id === itemId);
    if (!item) return;

    currentOrder = currentOrder.filter(i => i.id !== itemId);

    logAction('item_removed', { item: item.name });

    renderOrder();
    updateTotals();
}

// Update quantity
function updateQuantity(itemId, newQty) {
    if (newQty <= 0) {
        removeFromOrder(itemId);
        return;
    }

    const item = currentOrder.find(i => i.id === itemId);
    if (item) {
        item.quantity = newQty;
        logAction('quantity_updated', {
            item: item.name,
            new_quantity: newQty
        });
    }

    renderOrder();
    updateTotals();
}

// Render order items - FIXED: Properly handle empty state
function renderOrder() {
    const container = document.getElementById('orderItems');
    const emptyMsg = document.getElementById('emptyOrderMessage');

    if (!container) return; // Safety check

    if (currentOrder.length === 0) {
        if (emptyMsg) emptyMsg.style.display = 'block';
        // Clear only the order items, not the empty message
        const existingItems = container.querySelectorAll('.bg-gray-50');
        existingItems.forEach(item => item.remove());
        return;
    }

    if (emptyMsg) emptyMsg.style.display = 'none';

    // Build the HTML for all order items
    const orderHTML = currentOrder.map(item => `
        <div class="bg-gray-50 p-3 rounded flex items-center justify-between mb-2">
            <div class="flex-1">
                <div class="font-medium">${item.name}</div>
                <div class="text-sm text-gray-600">₱${item.price.toFixed(2)}</div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})"
                        class="w-6 h-6 bg-gray-300 rounded hover:bg-gray-400 flex items-center justify-center">
                    -
                </button>
                <span class="w-8 text-center font-semibold">${item.quantity}</span>
                <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})"
                        class="w-6 h-6 bg-gray-300 rounded hover:bg-gray-400 flex items-center justify-center">
                    +
                </button>
                <button onclick="removeFromOrder(${item.id})"
                        class="ml-2 text-red-500 hover:text-red-700">
                    🗑️
                </button>
            </div>
        </div>
    `).join('');

    // Set the HTML (empty message already hidden)
    container.innerHTML = orderHTML;
}

// Calculate totals
function calculateSubtotal() {
    return currentOrder.reduce((sum, item) => sum + (item.price * item.quantity), 0);
}

function calculateDiscount(subtotal) {
    const discountTypeEl = document.getElementById('discountType');
    if (!discountTypeEl) return 0;

    const discountType = discountTypeEl.value;
    switch(discountType) {
        case 'pwd': return subtotal * 0.20;
        case 'senior': return subtotal * 0.20;
        case 'manager': return subtotal * 0.15;
        default: return 0;
    }
}

function calculateTotal() {
    const subtotal = calculateSubtotal();
    const discount = calculateDiscount(subtotal);
    return subtotal - discount;
}

// Update totals display - FIXED: Added null checks
function updateTotals() {
    const subtotal = calculateSubtotal();
    const discount = calculateDiscount(subtotal);
    const total = calculateTotal();

    const subtotalEl = document.getElementById('subtotal');
    const discountEl = document.getElementById('discount');
    const totalEl = document.getElementById('total');
    const discountRow = document.getElementById('discountRow');

    if (subtotalEl) subtotalEl.textContent = `₱${subtotal.toFixed(2)}`;
    if (discountEl) discountEl.textContent = `-₱${discount.toFixed(2)}`;
    if (totalEl) totalEl.textContent = `₱${total.toFixed(2)}`;

    if (discountRow) {
        discountRow.style.display = discount > 0 ? 'flex' : 'none';
    }

    updateChange();
}

// Set payment method
function setPaymentMethod(method) {
    paymentMethod = method;

    const cashBtn = document.getElementById('cashBtn');
    const cardBtn = document.getElementById('cardBtn');
    const cashInputDiv = document.getElementById('cashInputDiv');

    // Update button styles
    if (cashBtn) {
        cashBtn.className = 'payment-btn p-2 rounded border ' +
            (method === 'cash' ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-50');
    }
    if (cardBtn) {
        cardBtn.className = 'payment-btn p-2 rounded border ' +
            (method === 'card' ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-50');
    }

    // Show/hide cash input
    if (cashInputDiv) {
        cashInputDiv.style.display = method === 'cash' ? 'block' : 'none';
    }

    logAction('payment_method_selected', { method });
}

// Update change calculation - FIXED: Added null checks
function updateChange() {
    if (paymentMethod !== 'cash') return;

    const cashReceivedEl = document.getElementById('cashReceived');
    const changeAmountEl = document.getElementById('changeAmount');
    const changeDisplayEl = document.getElementById('changeDisplay');

    if (!cashReceivedEl || !changeAmountEl || !changeDisplayEl) return;

    const cashReceived = parseFloat(cashReceivedEl.value) || 0;
    const total = calculateTotal();

    if (cashReceived >= total && cashReceived > 0) {
        const change = cashReceived - total;
        changeAmountEl.textContent = `₱${change.toFixed(2)}`;
        changeDisplayEl.style.display = 'block';
    } else {
        changeDisplayEl.style.display = 'none';
    }
}

// Process payment
async function processPayment() {
    if (currentOrder.length === 0) {
        alert('⚠️ No items in order!');
        return;
    }

    const total = calculateTotal();
    const subtotal = calculateSubtotal();
    const discount = calculateDiscount(subtotal);
    const discountTypeEl = document.getElementById('discountType');
    const discountType = discountTypeEl ? discountTypeEl.value : 'none';

    // Validate cash payment
    if (paymentMethod === 'cash') {
        const cashReceivedEl = document.getElementById('cashReceived');
        const cashReceived = cashReceivedEl ? parseFloat(cashReceivedEl.value) || 0 : 0;
        if (cashReceived < total) {
            alert('⚠️ Insufficient cash received!');
            logAction('payment_failed', { reason: 'insufficient_cash', total, received: cashReceived });
            return;
        }
    }

    const cashReceivedEl = document.getElementById('cashReceived');
    const cashReceived = paymentMethod === 'cash' && cashReceivedEl
        ? parseFloat(cashReceivedEl.value)
        : null;

    // Log payment
    await logAction('payment_processed', {
        order_items: currentOrder.map(i => ({
            name: i.name,
            price: i.price,
            quantity: i.quantity
        })),
        subtotal: subtotal.toFixed(2),
        discount_type: discountType,
        discount: discount.toFixed(2),
        total: total.toFixed(2),
        payment_method: paymentMethod,
        cash_received: cashReceived,
        change: cashReceived ? (cashReceived - total).toFixed(2) : null
    });

    // Update totals
    totalOrders++;
    totalRevenue += total;

    const totalOrdersEl = document.getElementById('totalOrders');
    const totalRevenueEl = document.getElementById('totalRevenue');

    if (totalOrdersEl) totalOrdersEl.textContent = totalOrders;
    if (totalRevenueEl) totalRevenueEl.textContent = `₱${totalRevenue.toFixed(2)}`;

    // Show receipt before clearing order
    showReceipt({
        orderNumber: totalOrders,
        items: [...currentOrder],
        subtotal: subtotal,
        discount: discount,
        discountType: discountType,
        total: total,
        paymentMethod: paymentMethod,
        cashReceived: cashReceived,
        change: cashReceived ? cashReceived - total : null
    });

    // Clear order
    currentOrder = [];
    if (cashReceivedEl) cashReceivedEl.value = '';
    if (discountTypeEl) discountTypeEl.value = 'none';

    const changeDisplayEl = document.getElementById('changeDisplay');
    if (changeDisplayEl) changeDisplayEl.style.display = 'none';

    renderOrder();
    updateTotals();
}

// Show receipt modal
function showReceipt(receiptData) {
    const modal = document.getElementById('receiptModal');
    if (!modal) return;

    // Set order number and date/time
    const orderNumEl = document.getElementById('receiptOrderNum');
    const dateTimeEl = document.getElementById('receiptDateTime');
    if (orderNumEl) orderNumEl.textContent = receiptData.orderNumber;
    if (dateTimeEl) {
        const now = new Date();
        dateTimeEl.textContent = now.toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    // Populate items
    const itemsContainer = document.getElementById('receiptItems');
    if (itemsContainer) {
        itemsContainer.innerHTML = receiptData.items.map(item => `
            <div class="flex justify-between text-sm mb-2">
                <div class="flex-1">
                    <div class="font-medium">${item.name}</div>
                    <div class="text-gray-600 text-xs">₱${item.price.toFixed(2)} x ${item.quantity}</div>
                </div>
                <div class="font-semibold">₱${(item.price * item.quantity).toFixed(2)}</div>
            </div>
        `).join('');
    }

    // Set totals
    const subtotalEl = document.getElementById('receiptSubtotal');
    const discountEl = document.getElementById('receiptDiscount');
    const discountTypeEl = document.getElementById('receiptDiscountType');
    const discountRowEl = document.getElementById('receiptDiscountRow');
    const totalEl = document.getElementById('receiptTotal');

    if (subtotalEl) subtotalEl.textContent = `₱${receiptData.subtotal.toFixed(2)}`;
    if (totalEl) totalEl.textContent = `₱${receiptData.total.toFixed(2)}`;

    if (receiptData.discount > 0 && discountRowEl) {
        discountRowEl.style.display = 'flex';
        if (discountEl) discountEl.textContent = `-₱${receiptData.discount.toFixed(2)}`;
        if (discountTypeEl) {
            const discountLabels = {
                'pwd': 'PWD 20%',
                'senior': 'Senior 20%',
                'manager': 'Manager 15%'
            };
            discountTypeEl.textContent = discountLabels[receiptData.discountType] || '';
        }
    } else if (discountRowEl) {
        discountRowEl.style.display = 'none';
    }

    // Set payment details
    const paymentMethodEl = document.getElementById('receiptPaymentMethod');
    const cashDetailsEl = document.getElementById('receiptCashDetails');
    const cashReceivedEl = document.getElementById('receiptCashReceived');
    const changeEl = document.getElementById('receiptChange');

    if (paymentMethodEl) paymentMethodEl.textContent = receiptData.paymentMethod.toUpperCase();

    if (receiptData.paymentMethod === 'cash' && cashDetailsEl) {
        cashDetailsEl.style.display = 'block';
        if (cashReceivedEl) cashReceivedEl.textContent = `₱${receiptData.cashReceived.toFixed(2)}`;
        if (changeEl) changeEl.textContent = `₱${receiptData.change.toFixed(2)}`;
    } else if (cashDetailsEl) {
        cashDetailsEl.style.display = 'none';
    }

    // Set session ID
    const sessionIdEl = document.getElementById('receiptSessionId');
    if (sessionIdEl) sessionIdEl.textContent = sessionId;

    // Show modal
    modal.classList.remove('hidden');
}

// Close receipt modal
function closeReceipt() {
    const modal = document.getElementById('receiptModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Submit simulation
async function submitSimulation() {
    if (isSubmitting) {
        console.log('Already submitting...');
        return;
    }

    if (!sessionId) {
        alert('⚠️ No active session! Please refresh the page.');
        return;
    }

    if (totalOrders === 0) {
        alert('⚠️ You must process at least one order before submitting!');
        return;
    }

    if (!confirm('Are you sure you want to submit this simulation? You cannot edit it after submission.')) {
        return;
    }

    isSubmitting = true;
    const submitBtn = document.getElementById('submitSimulationBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '⏳ Submitting...';
    }

    const sessionDuration = (Date.now() - sessionStartTime) / 1000 / 60; // minutes

    const metrics = {
        total_orders: totalOrders,
        total_revenue: totalRevenue.toFixed(2),
        avg_order_value: totalOrders > 0 ? (totalRevenue / totalOrders).toFixed(2) : 0,
        session_duration_minutes: sessionDuration.toFixed(2),
        total_actions: actionsCount,
        orders_per_minute: totalOrders > 0 ? (totalOrders / sessionDuration).toFixed(2) : 0
    };

    try {
        console.log('Submitting simulation...', {
            sessionId,
            activityId: ACTIVITY_ID,
            metrics
        });

        const response = await fetch(`{{ url('/simulation/submit') }}/${sessionId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                activity_id: ACTIVITY_ID,
                metrics: metrics
            })
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', Object.fromEntries(response.headers.entries()));

        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Non-JSON response:', text);
            throw new Error('Server returned an HTML error page. Check console for details.');
        }

        const data = await response.json();
        console.log('Submit response:', data);

        if (!response.ok) {
            throw new Error(data.message || data.error || `Server error: ${response.status}`);
        }

        if (data.success) {
            alert('✅ ' + (data.message || 'Simulation submitted successfully!'));

            // Redirect to activity details page
            window.location.href = `{{ url('/student/activity/activity_details') }}/${SECTION_ID}/${ACTIVITY_ID}`;
        } else {
            throw new Error(data.message || 'Failed to submit simulation');
        }
    } catch (error) {
        console.error('Submit error:', error);
        alert('❌ Failed to submit simulation: ' + error.message);

        // Re-enable button
        isSubmitting = false;
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Submit Simulation';
        }
    }
}

// Exit simulation
function exitSimulation() {
    if (confirm('Are you sure you want to exit? Unsaved progress will be lost.')) {
        window.location.href = `{{ url('/student/activity') }}/${SECTION_ID}`;
    }
}

// Session timer
function startTimer() {
    setInterval(() => {
        const elapsed = Math.floor((Date.now() - sessionStartTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        const timerEl = document.getElementById('sessionTimer');
        if (timerEl) {
            timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
    }, 1000);
}
</script>
</x-layouts>
