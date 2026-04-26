<div>
    <div class="flex flex-col md:flex-row h-[76vh]">
        <ul class="flex-column space-y space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0 hidden sm:block">
            <li wire:click="switchTab('cash')" class=" cursor-pointer">
                <div class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ ($currentTab === 'cash') ? 'text-white bg-blue-700  active  dark:bg-blue-600' : 'hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white' }}" aria-current="page">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 {{ ($currentTab === 'cash') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>Cash
                </div>
            </li>
            <li wire:click="switchTab('debit')" class=" cursor-pointer">
                <div class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ ($currentTab === 'debit') ? 'text-white bg-blue-700  active  dark:bg-blue-600' : 'hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 {{ ($currentTab === 'debit') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>Card
                </div>
            </li>
            {{-- <li wire:click="switchTab('credit')" class=" cursor-pointer">
                <div class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ ($currentTab === 'credit') ? 'text-white bg-blue-700  active  dark:bg-blue-600' : 'hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 {{ ($currentTab === 'credit') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>Credit
                </div>
            </li> --}}
            <li wire:click="switchTab('split')" class=" cursor-pointer">
                <div class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ ($currentTab === 'split') ? 'text-white bg-blue-700  active  dark:bg-blue-600' : 'hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 {{ ($currentTab === 'split') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>Split
                </div>
            </li>
        </ul>
        <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 sm:hidden mb-4"
        wire:change="switchTab($event.target.value)">
                <option value="cash">Cash</option>
                <option value="debit">Card</option>
                <option value="split">Split</option>
        </select>
        <div class="p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full overflow-auto">
            @if ($currentTab === 'cash')
            <div class="grid grid-cols-[1fr_3fr] gap-5">
                <label for="amount-due" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount Due: </label>
                <input type="number" id="amount-due" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $amount_due }}" disabled/>

                <label for="recieved-amount" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Recieved Amount: </label>
                <input type="number" id="recieved-amount" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model.live.debounce.1000ms="paymentData.0.recieved_amount"/>

                <div class=" col-span-2 grid grid-cols-3">
                    <button type="button"
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        wire:click="addCashAmount(1)">
                            ₱1
                    </button>
                    <button type="button"
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        wire:click="addCashAmount(5)">
                            ₱5
                    </button>
                    <button type="button"
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        wire:click="addCashAmount(10)">
                            ₱10
                    </button>
                    <button type="button"
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        wire:click="addCashAmount(20)">
                            ₱20
                    </button>
                    <button type="button"
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        wire:click="addCashAmount(50)">
                            ₱50
                    </button>
                    <button type="button"
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        wire:click="addCashAmount(100)">
                            ₱100
                    </button>
                    <button type="button"
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        wire:click="addCashAmount(500)">
                            ₱500
                    </button>
                    <button type="button"
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        wire:click="addCashAmount(1000)">
                            ₱ 1000
                    </button>

                    <button type="button"
                        class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 flex justify-center content-center"
                        wire:click="clearCashAmount()">
                        Clear
                    </button>
                </div>

                <label for="change" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Change: </label>
                <input type="number" id="change" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model="paymentData.0.change" disabled/>
            </div>
            @elseif($currentTab === 'debit')
            <div class="grid grid-cols-[1fr_3fr] gap-5">
                <label for="card-type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card Type: </label>
                <select id="card-type"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    wire:model="paymentData.0.card_type">
                        <option value="visa">VISA</option>
                        <option value="mastercard">Mastercard</option>
                        <option value="jcb">JCB</option>
                        <option value="unionpay">UnionPay</option>
                </select>

                <label for="payment-method" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Payment Method: </label>
                <select id="payment-method"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    wire:model="paymentData.0.payment_method">
                        <option value="credit">Credit Card</option>
                        <option value="debit">Debit Card</option>
                </select>

                <label for="bank" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bank</label>
                <select id="bank"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    wire:model="paymentData.0.bank">
                    <option value="bdo">BDO</option>
                    <option value="bpi">BPI</option>
                    <option value="metrobank">Metrobank</option>
                    <option value="landbank">Landbank</option>
                </select>

                <label for="auth-code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Authorization Code</label>
                <input type="text" id="auth-code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model="paymentData.0.auth_code">

                <label for="ref-num" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reference Number: </label>
                <input type="text" id="ref-num" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model="paymentData.0.ref_number">

                <label for="amount" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount: </label>
                <input type="number" id="amount" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $amount_due }}" disabled/>
            </div>
            @elseif($currentTab === 'credit')

            @elseif($currentTab === 'split')
            <div class="grid grid-cols-1 gap-1 sm:gap-5">
                <div class=" grid grid-cols-2 sm:grid-cols-3 gap-5">
                    {{-- <button 
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ ($currentTab === 'cash') ? 'text-white bg-blue-700  active  dark:bg-blue-600' : 'hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white' }}" aria-current="page"
                        wire:click="addSplitPayment('cash')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 {{ ($currentTab === 'cash') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>Cash +
                    </button> --}}

                    {{-- <button 
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full {{ ($currentTab === 'debit') ? 'text-white bg-blue-700  active  dark:bg-blue-600' : 'hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white' }}"
                        wire:click="addSplitPayment('debit')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 {{ ($currentTab === 'debit') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>Card +
                    </button> --}}

                    <button type="button" 
                        class="inline-flex items-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        wire:click="addSplitPayment('cash')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 {{ ($currentTab === 'cash') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                            </svg>Cash +
                    </button>

                    <button type="button" 
                        class="inline-flex items-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        wire:click="addSplitPayment('debit')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 {{ ($currentTab === 'debit') ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>Card +
                    </button>

                    <label class="inline-flex items-center cursor-pointer  border dark:border-gray-600 rounded-lg px-5 py-2.5">
                        <input type="checkbox" value="true" class="sr-only peer" wire:model.change="isSplitBill">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-300">Split Bill</span>
                    </label>
                </div>
                <div class="grid gap-10 overflow-auto">
                    @foreach ($paymentData as $index => $payment)
                        @if($payment['type'] === 'cash')
                        <div class="grid grid-cols-[1fr_3fr] gap-5 border-b-2 pb-5">
                            <label for="amount-due" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount: </label>
                            <input type="number" id="amount-due" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model.live.debounce.1000ms="paymentData.{{ $index }}.amount" {{ ($isSplitBill) ? 'disabled' : '' }}/>

                            <label for="recieved-amount" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Recieved Amount: </label>
                            <input type="number" id="recieved-amount" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model.live.debounce.1000ms="paymentData.{{ $index }}.recieved_amount"/>

                            <label for="change" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Change: </label>
                            <input type="number" id="change" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model="paymentData.{{ $index }}.change" disabled/>

                            <div></div>

                            <div class=" w-full inline-flex justify-end gap-5">
                                @if($isSplitBill)
                                <button type="button" 
                                    class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" wire:click="openSelectModal({{ $index }})">
                                        Select Items
                                </button>
                                @endif

                                <button type="button" 
                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                    wire:click="removeSplitPayment({{ $index }})">
                                        Remove
                                </button>
                            </div>
                        </div>
                        @elseif($payment['type'] === 'card')
                        <div class="grid grid-cols-[1fr_3fr] gap-5 border-b-2 pb-5">
                            <label for="card-type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card Type: </label>
                            <select id="card-type"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                wire:model="paymentData.{{ $index }}.card_type">
                                    <option value="visa">VISA</option>
                                    <option value="mastercard">Mastercard</option>
                                    <option value="jcb">JCB</option>
                                    <option value="unionpay">UnionPay</option>
                            </select>

                            <label for="payment-method" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Payment Method: </label>
                            <select id="payment-method"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                wire:model="paymentData.{{ $index }}.payment_method">
                                    <option value="credit">Credit Card</option>
                                    <option value="debit">Debit Card</option>
                            </select>

                            <label for="bank" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bank</label>
                            <select id="bank"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                wire:model="paymentData.{{ $index }}.bank">
                                <option value="bdo">BDO</option>
                                <option value="bpi">BPI</option>
                                <option value="metrobank">Metrobank</option>
                                <option value="landbank">Landbank</option>
                            </select>

                            <label for="auth-code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Authorization Code</label>
                            <input type="text" id="auth-code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model="paymentData.{{ $index }}.auth_code">

                            <label for="ref-num" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reference Number: </label>
                            <input type="text" id="ref-num" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model="paymentData.{{ $index }}.ref_number">

                            <label for="amount" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount: </label>
                            <input type="number" id="amount" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model.live.debounce.1000ms="paymentData.{{ $index }}.amount" {{ ($isSplitBill) ? 'disabled' : '' }}/>

                            <div></div>

                            <div class=" w-full inline-flex justify-end gap-5">
                                @if($isSplitBill)
                                <button type="button" 
                                    class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" wire:click="openSelectModal({{ $index }})">
                                        Select Items
                                </button>
                                @endif

                                <button type="button" 
                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                    wire:click="removeSplitPayment({{ $index }})">
                                        Remove
                                </button>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="flex justify-between flex-col sm:flex-row">

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 sm:ml-[6.5rem] p-2 m-2 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg">
            <label for="amount-due" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount Due: </label>
            <input type="number" id="amount-due" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $amount_due }}" disabled/>

            <label for="tota-payment" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Payment: </label>
            <input type="number" id="tota-payment" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $totalPayment }}" disabled/>
        </div>

        <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 m-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" wire:click="finishPayment">Confirm Payment</button>
    </div>
    @if($errors->any())
        <ul class="text-white">
            @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif

    {{-- DISCOUNT MODAL --}}
    @if ($showSelectModal)
        @teleport('body')
        <div id="default-modal" tabindex="-1" aria-hidden="true" class=" fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <form wire:submit.prevent="saveItemDiscount" class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Item Select
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal" wire:click="closeSelectModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4 overflow-y-scroll h-96">
                        <ul class="grid w-full gap-3 md:grid-cols-1">
                            @foreach ($orders as $order)
                            <li>
                                @php
                                    $usedOrders = collect($splitData)->flatten();
                                @endphp
                                <input type="checkbox" id="item-{{ $order['uid'] }}" value="{{ $order['uid'] }}" class="hidden peer" wire:model.change="splitData.{{ $paymentIndex }}" 
                                @disabled(
                                    $usedOrders->contains($order['uid']) 
                                    && !in_array($order['uid'], $splitData[$paymentIndex] ?? [])
                                )>
                                    <label for="item-{{ $order['uid'] }}" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 dark:peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                                    <div class="block">
                                        <div class="w-full text-lg font-semibold">{{ $order['menu_name'] }}</div>
                                        <div class="w-full text-xs">A JavaScript library for building user interfaces.</div>
                                    </div>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="default-modal" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                        <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" wire:click="closeSelectModal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        @endteleport
    @endif

    @if ($showReceiptModal)
    <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
        <div class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-[350px] mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-5">
                <div class="text-center border-b border-dashed border-gray-300 dark:border-gray-600 pb-3 mb-4">
                    <div class="text-lg font-semibold">RestauSim</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">123 Demo Street</div>
                    <div class="mt-2 text-xs font-semibold">OFFICIAL RECEIPT (SIMULATION)</div>
                </div>
                @if ($payment_method === 'split' && count($splitReceipts) > 0)
                    <div class="mb-3 text-center text-xs">
                        Split Payment {{ $splitReceipts[$currentReceiptIndex]['split_index'] }} of {{ $splitReceipts[$currentReceiptIndex]['split_total'] }}
                    </div>
                    <div class="flex gap-2 justify-center mb-4">
                        @foreach ($splitReceipts as $i => $r)
                            <button type="button" class="px-2 py-1 rounded text-xs {{ $currentReceiptIndex === $i ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-200' }}" wire:click="setReceiptIndex({{ $i }})">Receipt {{ $i + 1 }}</button>
                        @endforeach
                    </div>
                    @php $r = $splitReceipts[$currentReceiptIndex]; @endphp
                    <div class="text-xs space-y-1 mb-3">
                        <div class="flex justify-between"><span>Order #</span><span>{{ $r['order_number'] }}</span></div>
                        <div class="flex justify-between"><span>Type</span><span>{{ ucfirst($r['order_type']) }}</span></div>
                        @if($r['table_name']) <div class="flex justify-between"><span>Table</span><span>{{ $r['table_name'] }}</span></div> @endif
                        <div class="flex justify-between"><span>Cashier</span><span>{{ $r['cashier_name'] }}</span></div>
                        <div class="flex justify-between"><span>Date</span><span>{{ $r['datetime'] }}</span></div>
                    </div>
                    <div class="mb-4">
                        @foreach ($r['items'] as $item)
                            <div class="flex justify-between text-sm mb-2">
                                <div class="flex-1">
                                    <div class="font-medium">{{ $item['menu_name'] }} × {{ $item['quantity'] }}</div>
                                    @foreach ($item['customizations'] as $c)
                                        <div class="text-gray-600 dark:text-gray-400 text-xs">+ {{ $c['name'] }}</div>
                                    @endforeach
                                </div>
                                @php
                                    $lineTotal = $item['total_item_amount']
                                        ?? ($item['line_gross_amount'] ?? 0);

                                    // fallback if total_item_amount is 0 but we have price_at_sale + quantity
                                    if (($lineTotal == 0 || $lineTotal === "0") && isset($item['price_at_sale'], $item['quantity'])) {
                                        $lineTotal = (float) $item['price_at_sale'] * (int) $item['quantity'];
                                    }
                                @endphp

                                <div class="font-semibold">₱{{ number_format($lineTotal, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-dashed border-gray-300 dark:border-gray-600 pt-3 space-y-2 text-sm">
                        <div class="flex justify-between"><span>Subtotal</span><span>₱{{ number_format($r['subtotal'], 2) }}</span></div>
                        @if(($r['discount'] ?? 0) > 0)
                            <div class="flex justify-between text-red-600"><span>Discount</span><span>-₱{{ number_format($r['discount'], 2) }}</span></div>
                        @endif
                        <div class="flex justify-between font-bold border-t pt-2"><span>Grand Total</span><span>₱{{ number_format($r['grand_total'], 2) }}</span></div>
                    </div>
                    <div class="border-t border-dashed pt-3 mt-3 space-y-1 text-sm">
                        <div class="flex justify-between"><span>Assigned Amount</span><span>₱{{ number_format($r['assigned_amount'], 2) }}</span></div>
                        <div class="flex justify-between"><span>Payment Method</span><span class="uppercase">{{ $r['payment_method'] }}</span></div>
                        <div class="flex justify-between"><span>Amount Paid</span><span>₱{{ number_format($r['amount_paid'], 2) }}</span></div>
                        <div class="flex justify-between font-semibold text-green-600"><span>Change</span><span>₱{{ number_format($r['change'], 2) }}</span></div>
                    </div>
                    <div class="mt-5 flex gap-2">
                        <button type="button" class="flex-1 bg-gray-200 dark:bg-gray-700 dark:text-gray-200 rounded py-2 text-sm" onclick="window.print()">Print</button>
                        @if (($currentReceiptIndex + 1) < count($splitReceipts))
                            <button type="button" class="flex-1 bg-blue-600 text-white rounded py-2 text-sm" wire:click="nextReceipt">Next Receipt</button>
                        @endif
                        <button type="button" class="flex-1 bg-gray-900 text-white rounded py-2 text-sm" wire:click="closeReceipt">Close</button>
                    </div>
                @else
                    <div class="text-xs space-y-1 mb-3">
                        <div class="flex justify-between"><span>Order #</span><span>{{ $receipt['order_number'] }}</span></div>
                        <div class="flex justify-between"><span>Type</span><span>{{ ucfirst($receipt['order_type']) }}</span></div>
                        @if($receipt['table_name']) <div class="flex justify-between"><span>Table</span><span>{{ $receipt['table_name'] }}</span></div> @endif
                        <div class="flex justify-between"><span>Cashier</span><span>{{ $receipt['cashier_name'] }}</span></div>
                        <div class="flex justify-between"><span>Date</span><span>{{ $receipt['datetime'] }}</span></div>
                    </div>
                    <div class="mb-4">
                        @foreach ($receipt['items'] as $item)
                            <div class="flex justify-between text-sm mb-2">
                                <div class="flex-1">
                                    <div class="font-medium">{{ $item['menu_name'] }} × {{ $item['quantity'] }}</div>
                                    @foreach ($item['customizations'] as $c)
                                        <div class="text-gray-600 dark:text-gray-400 text-xs">+ {{ $c['name'] }}</div>
                                    @endforeach
                                </div>
                                @php
                                    $lineTotal = $item['total_item_amount']
                                        ?? ($item['line_gross_amount'] ?? 0);

                                    // fallback if total_item_amount is 0 but we have price_at_sale + quantity
                                    if (($lineTotal == 0 || $lineTotal === "0") && isset($item['price_at_sale'], $item['quantity'])) {
                                        $lineTotal = (float) $item['price_at_sale'] * (int) $item['quantity'];
                                    }
                                @endphp

                                <div class="font-semibold">₱{{ number_format($lineTotal, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center border-b border-dashed border-gray-300 dark:border-gray-600 pb-3 mb-4">
                        <div class="flex justify-between"><span>Subtotal</span><span>₱{{ number_format($receipt['subtotal'], 2) }}</span></div>
                        @if(($receipt['discount'] ?? 0) > 0)
                            <div class="flex justify-between text-red-600"><span>Discount</span><span>-₱{{ number_format($receipt['discount'], 2) }}</span></div>
                        @endif
                        <div class="flex justify-between font-bold border-t pt-2"><span>Grand Total</span><span>₱{{ number_format($receipt['grand_total'], 2) }}</span></div>
                    </div>
                    <div class="border-t border-dashed border-gray-300 dark:border-gray-600 pt-3 space-y-2 text-sm">
                        <div class="flex justify-between"><span>Payment Method</span><span class="uppercase">{{ $receipt['payment_method'] }}</span></div>
                        <div class="flex justify-between"><span>Amount Paid</span><span>₱{{ number_format($receipt['amount_paid'], 2) }}</span></div>
                        <div class="flex justify-between font-semibold text-green-600 dark:text-green-400"><span>Change</span><span>₱{{ number_format($receipt['change'], 2) }}</span></div>
                    </div>
                    <div class="mt-5 flex gap-2">
                        <button type="button" class="flex-1 bg-gray-900 text-white dark:bg-white dark:text-gray-900 rounded py-2 text-sm" wire:click="closeReceipt"> Close</button>
                    </div>
                @endif
                <div class="text-center mt-4 pt-3 border-t border-dashed border-gray-300 dark:border-gray-600">
                    <div class="text-xs text-gray-600 dark:text-gray-300">Thank you!</div>
                    <div class="text-xs text-gray-400">Simulation Only</div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>