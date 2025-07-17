import { defineStore } from 'pinia'
import { ref } from 'vue'
import { sendApi } from '@/utils/api'
export const useWalletStore = defineStore('wallet', () => {
    const transactions = ref([])
    const transactionsLoaded = ref(false)
    const discountCards = ref([])
    const discountCardsLoaded = ref(false)
    const loading = ref(false)
    const cards = ref([])
    const withdrawals = ref([])
    const fetchWithdrawals = async () => {
        const res = await sendApi({ control: 'wallet', action: 'get_withdrawals' })
        if (res.status === 'success') withdrawals.value = res.data || []
    }
    const requestWithdrawal = async (data) => {
        const res = await sendApi({ control: 'wallet', action: 'request_withdrawal', data })
        if (res.status === 'success') await fetchWithdrawals()
        return res
    }
    const fetchCards = async () => {
        const res = await sendApi({ control: 'wallet', action: 'get_cards' })
        if (res.status === 'success') cards.value = res.data || []
    }
    const addCard = async (data) => {
        const res = await sendApi({ control: 'wallet', action: 'add_card', data })
        if (res.status === 'success') await fetchCards()
        return res
    }
    const deleteCard = async (id) => {
        const res = await sendApi({ control: 'wallet', action: 'delete_card', data: { id } })
        if (res.status === 'success') cards.value = cards.value.filter(c => c.id !== id)
        return res
    }
    const fetchTransactions = async ({ limit = 10, offset = 0, append = false }) => {
        const res = await sendApi({ control: 'wallet', action: 'get_transactions' , data: { limit, offset } })
        // if (res.status === 'success') {
        //     // this.cards = append ? [...this.cards, ...newCards] : [...newCards, ...this.cards]
        //     // this.total = res.count_all
        //     const newIds = res.data.map(i => i.id).sort()
        //     const oldIds = transactions.value.map(i => i.id).sort()
        //     if (JSON.stringify(newIds) !== JSON.stringify(oldIds)) {
        //         transactions.value = res.data
        //     }
        //     transactionsLoaded.value = true
        // }
        if (res.status === 'success') {
            const newTx = res.data || []
            if (append) {
                const existingIds = new Set(transactions.value.map(tx => tx.id))
                const merged = [...transactions.value]
                for (const tx of newTx) {
                    if (!existingIds.has(tx.id)) merged.push(tx)
                }
                transactions.value = merged
            } else {
                transactions.value = newTx
            }
            transactionsLoaded.value = true
        }
    }
    const fetchDiscountCards = async () => {
        const res = await sendApi({ control: 'wallet', action: 'get_discount_cards' })
        if (res.status === 'success') {
            const newIds = res.data.map(i => i.id).sort()
            const oldIds = discountCards.value.map(i => i.id).sort()
            if (JSON.stringify(newIds) !== JSON.stringify(oldIds)) {
                discountCards.value = res.data
            }
            discountCardsLoaded.value = true
        }
    }
    return {
        transactions,
        transactionsLoaded,
        discountCards,
        discountCardsLoaded,
        loading,
        cards,
        withdrawals,
        fetchWithdrawals,
        requestWithdrawal,
        fetchTransactions,
        fetchDiscountCards,
        fetchCards,
        addCard,
        deleteCard
        // startPolling,
        // stopPolling,
    }
})
