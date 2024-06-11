// Extra icons

import { defineComponent } from 'vue'

export const CheckCircleIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        )
    },
})

export const XCircleIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        )
    },
})

export const ExclamationCircleIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
        )
    },
})

export const CheckIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        )
    },
})

export const ChevronDownIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        )
    },
})

export const XMarkIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        )
    },
})

export const GlobeAltIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
            </svg>
        )
    },
})

export const Bars3BottomLeftIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
            </svg>
        )
    },
})

export const ArrowLeftIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        )
    },
})

export const EmptyCircleIcon = defineComponent({
    setup() {
        return () => (
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M20.3149 15.4442C20.7672 14.3522 21 13.1819 21 12C21 9.61305 20.0518 7.32387 18.364 5.63604C16.6761 3.94821 14.3869 3 12 3C9.61305 3 7.32387 3.94821 5.63604 5.63604C3.94821 7.32387 3 9.61305 3 12C3 13.1819 3.23279 14.3522 3.68508 15.4442C4.13738 16.5361 4.80031 17.5282 5.63604 18.364C6.47177 19.1997 7.46392 19.8626 8.55585 20.3149C9.64778 20.7672 10.8181 21 12 21C13.1819 21 14.3522 20.7672 15.4442 20.3149C16.5361 19.8626 17.5282 19.1997 18.364 18.364C19.1997 17.5282 19.8626 16.5361 20.3149 15.4442Z"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        )
    },
})

export const HomeIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
        )
    },
})

export const UserCircleIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
        )
    },
})

export const LogOutIcon = defineComponent({
    setup() {
        return () => (
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.3333 14.1667L17.5 10M17.5 10L13.3333 5.83333M17.5 10H7.5M7.5 2.5H6.5C5.09987 2.5 4.3998 2.5 3.86502 2.77248C3.39462 3.01217 3.01217 3.39462 2.77248 3.86502C2.5 4.3998 2.5 5.09987 2.5 6.5V13.5C2.5 14.9001 2.5 15.6002 2.77248 16.135C3.01217 16.6054 3.39462 16.9878 3.86502 17.2275C4.3998 17.5 5.09987 17.5 6.5 17.5H7.5" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        )
    },
})

export const Users01Icon = defineComponent({
    setup() {
        return () => (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M22 21V19C22 17.1362 20.7252 15.5701 19 15.126M15.5 3.29076C16.9659 3.88415 18 5.32131 18 7C18 8.67869 16.9659 10.1159 15.5 10.7092M17 21C17 19.1362 17 18.2044 16.6955 17.4693C16.2895 16.4892 15.5108 15.7105 14.5307 15.3045C13.7956 15 12.8638 15 11 15H8C6.13623 15 5.20435 15 4.46927 15.3045C3.48915 15.7105 2.71046 16.4892 2.30448 17.4693C2 18.2044 2 19.1362 2 21M13.5 7C13.5 9.20914 11.7091 11 9.5 11C7.29086 11 5.5 9.20914 5.5 7C5.5 4.79086 7.29086 3 9.5 3C11.7091 3 13.5 4.79086 13.5 7Z"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        )
    },
})

export const CoinsHandIcon = defineComponent({
    setup() {
        return () => (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M13.5295 8.35186C12.9571 8.75995 12.2566 9 11.5 9C9.567 9 8 7.433 8 5.5C8 3.567 9.567 2 11.5 2C12.753 2 13.8522 2.65842 14.4705 3.64814M6 20.0872H8.61029C8.95063 20.0872 9.28888 20.1277 9.61881 20.2086L12.3769 20.8789C12.9753 21.0247 13.5988 21.0388 14.2035 20.9214L17.253 20.3281C18.0585 20.1712 18.7996 19.7854 19.3803 19.2205L21.5379 17.1217C22.154 16.5234 22.154 15.5524 21.5379 14.9531C20.9832 14.4134 20.1047 14.3527 19.4771 14.8103L16.9626 16.6449C16.6025 16.9081 16.1643 17.0498 15.7137 17.0498H13.2855L14.8311 17.0498C15.7022 17.0498 16.4079 16.3633 16.4079 15.5159V15.2091C16.4079 14.5055 15.9156 13.892 15.2141 13.7219L12.8286 13.1417C12.4404 13.0476 12.0428 13 11.6431 13C10.6783 13 8.93189 13.7988 8.93189 13.7988L6 15.0249M20 6.5C20 8.433 18.433 10 16.5 10C14.567 10 13 8.433 13 6.5C13 4.567 14.567 3 16.5 3C18.433 3 20 4.567 20 6.5ZM2 14.6L2 20.4C2 20.9601 2 21.2401 2.10899 21.454C2.20487 21.6422 2.35785 21.7951 2.54601 21.891C2.75992 22 3.03995 22 3.6 22H4.4C4.96005 22 5.24008 22 5.45399 21.891C5.64215 21.7951 5.79513 21.6422 5.89101 21.454C6 21.2401 6 20.9601 6 20.4V14.6C6 14.0399 6 13.7599 5.89101 13.546C5.79513 13.3578 5.64215 13.2049 5.45399 13.109C5.24008 13 4.96005 13 4.4 13L3.6 13C3.03995 13 2.75992 13 2.54601 13.109C2.35785 13.2049 2.20487 13.3578 2.10899 13.546C2 13.7599 2 14.0399 2 14.6Z"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        )
    },
})

export const Speedometer04 = defineComponent({
    setup() {
        return () => (
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.7878 13.3333C15.4468 12.3884 15.8333 11.2394 15.8333 10C15.8333 9.57093 15.787 9.15268 15.6991 8.74997M5.21223 13.3333C4.55317 12.3885 4.16666 11.2394 4.16666 10C4.16666 6.77834 6.77833 4.16667 9.99999 4.16667C10.3518 4.16667 10.6963 4.19781 11.0309 4.25748M13.7499 6.25001L9.99991 10M18.3333 10C18.3333 14.6024 14.6024 18.3333 9.99999 18.3333C5.39762 18.3333 1.66666 14.6024 1.66666 10C1.66666 5.39763 5.39762 1.66667 9.99999 1.66667C14.6024 1.66667 18.3333 5.39763 18.3333 10ZM10.8333 10C10.8333 10.4602 10.4602 10.8333 9.99999 10.8333C9.53975 10.8333 9.16666 10.4602 9.16666 10C9.16666 9.53977 9.53975 9.16667 9.99999 9.16667C10.4602 9.16667 10.8333 9.53977 10.8333 10Z" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        )
    },
})

export const CreditCardEditIcon = defineComponent({
    setup() {
        return () => (
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.66666 8.33332H18.3333V6.83332C18.3333 5.8999 18.3333 5.43319 18.1517 5.07667C17.9919 4.76307 17.7369 4.5081 17.4233 4.34831C17.0668 4.16666 16.6001 4.16666 15.6667 4.16666H4.33332C3.3999 4.16666 2.93319 4.16666 2.57667 4.34831C2.26307 4.5081 2.0081 4.76307 1.84831 5.07667C1.66666 5.43319 1.66666 5.8999 1.66666 6.83332V13.1667C1.66666 14.1001 1.66666 14.5668 1.84831 14.9233C2.0081 15.2369 2.26307 15.4919 2.57667 15.6517C2.93319 15.8333 3.3999 15.8333 4.33332 15.8333H9.16666M12.0833 17.5L13.7708 17.1625C13.9179 17.1331 13.9915 17.1184 14.0601 17.0914C14.121 17.0676 14.1789 17.0366 14.2325 16.9992C14.293 16.957 14.346 16.904 14.4521 16.7979L17.9167 13.3333C18.3769 12.8731 18.3769 12.1269 17.9167 11.6667C17.4564 11.2064 16.7102 11.2064 16.25 11.6667L12.7854 15.1312C12.6793 15.2373 12.6263 15.2904 12.5841 15.3508C12.5467 15.4044 12.5158 15.4623 12.4919 15.5232C12.465 15.5918 12.4502 15.6654 12.4208 15.8125L12.0833 17.5Z" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        )
    }
})

export const CreditCardDownloadIcon = defineComponent({
    setup() {
        return () => (
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.49999 14.1667L9.99999 16.6667M9.99999 16.6667L12.5 14.1667M9.99999 16.6667V10.8333M18.3333 7.50001H1.66666M4.58332 15H4.33332C3.3999 15 2.93319 15 2.57667 14.8184C2.26307 14.6586 2.0081 14.4036 1.84831 14.09C1.66666 13.7335 1.66666 13.2668 1.66666 12.3333V6.00001C1.66666 5.06659 1.66666 4.59988 1.84831 4.24336C2.0081 3.92976 2.26307 3.67479 2.57667 3.515C2.93319 3.33334 3.3999 3.33334 4.33332 3.33334H15.6667C16.6001 3.33334 17.0668 3.33334 17.4233 3.515C17.7369 3.67479 17.9919 3.92976 18.1517 4.24336C18.3333 4.59988 18.3333 5.06659 18.3333 6.00001V12.3333C18.3333 13.2668 18.3333 13.7335 18.1517 14.09C17.9919 14.4036 17.7369 14.6586 17.4233 14.8184C17.0668 15 16.6001 15 15.6667 15H15.4167" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        )
    }
})

export const ArrowsUpIcon = defineComponent({
    setup() {
        return () => (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 20V4M7 4L3 8M7 4L11 8M17 20V9M17 9L13 13M17 9L21 13" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        )
    },
})

export const ArrowsDownIcon = defineComponent({
    setup() {
        return () => (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17 4V15M17 15L13 11M17 15L21 11M7 4V20M7 20L3 16M7 20L11 16" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        )
    },
})

export const SwitchVertical01Icon = defineComponent({
    setup() {
        return () => (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17 4V20M17 20L13 16M17 20L21 16M7 20V4M7 4L3 8M7 4L11 8" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        )
    },
})

export const ChevronLeftIcon = defineComponent({
    setup() {
        return () => (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        )
    },
})

export const ChevronRightIcon = defineComponent({
    setup() {
        return () => (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        )
    },
})

export const ChevronLeftDoubleIcon = defineComponent({
    setup() {
        return () => (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 17L13 12L18 7M11 17L6 12L11 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        )
    },
})

export const ChevronRightDoubleIcon = defineComponent({
    setup() {
        return () => (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 17L11 12L6 7M13 17L18 12L13 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        )
    },
})

export const PlusCircleIcon = defineComponent({
    setup() {
        return () => (
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_373_3606)">
                    <path d="M10.0001 6.6665V13.3332M6.66675 9.99984H13.3334M18.3334 9.99984C18.3334 14.6022 14.6025 18.3332 10.0001 18.3332C5.39771 18.3332 1.66675 14.6022 1.66675 9.99984C1.66675 5.39746 5.39771 1.6665 10.0001 1.6665C14.6025 1.6665 18.3334 5.39746 18.3334 9.99984Z" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" />
                </g>
                <defs>
                    <clipPath id="clip0_373_3606">
                        <rect width="20" height="20" fill="white" />
                    </clipPath>
                </defs>
            </svg>
        )
    },
})

export const SearchIcon = defineComponent({
    setup() {
        return () => (
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.5 17.5L14.5834 14.5833M16.6667 9.58333C16.6667 13.4954 13.4954 16.6667 9.58333 16.6667C5.67132 16.6667 2.5 13.4954 2.5 9.58333C2.5 5.67132 5.67132 2.5 9.58333 2.5C13.4954 2.5 16.6667 5.67132 16.6667 9.58333Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        )
    },
})

export const FilterIcon = defineComponent({
    setup() {
        return () => (
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 10H15M2.5 5H17.5M7.5 15H12.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        )
    },
})

export const XIcon = defineComponent({
    setup() {
        return () => (
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        )
    },
})

export const DuplicateIcon = defineComponent({
    setup() {
        return () => (
            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 15C4.06812 15 3.60218 15 3.23463 14.8478C2.74458 14.6448 2.35523 14.2554 2.15224 13.7654C2 13.3978 2 12.9319 2 12V5.2C2 4.0799 2 3.51984 2.21799 3.09202C2.40973 2.71569 2.71569 2.40973 3.09202 2.21799C3.51984 2 4.0799 2 5.2 2H12C12.9319 2 13.3978 2 13.7654 2.15224C14.2554 2.35523 14.6448 2.74458 14.8478 3.23463C15 3.60218 15 4.06812 15 5M12.2 22H18.8C19.9201 22 20.4802 22 20.908 21.782C21.2843 21.5903 21.5903 21.2843 21.782 20.908C22 20.4802 22 19.9201 22 18.8V12.2C22 11.0799 22 10.5198 21.782 10.092C21.5903 9.71569 21.2843 9.40973 20.908 9.21799C20.4802 9 19.9201 9 18.8 9H12.2C11.0799 9 10.5198 9 10.092 9.21799C9.71569 9.40973 9.40973 9.71569 9.21799 10.092C9 10.5198 9 11.0799 9 12.2V18.8C9 19.9201 9 20.4802 9.21799 20.908C9.40973 21.2843 9.71569 21.5903 10.092 21.782C10.5198 22 11.0799 22 12.2 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>)
    },
})

export const Upload01Icon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M17.5 12.5V13.5C17.5 14.9001 17.5 15.6002 17.2275 16.135C16.9878 16.6054 16.6054 16.9878 16.135 17.2275C15.6002 17.5 14.9001 17.5 13.5 17.5H6.5C5.09987 17.5 4.3998 17.5 3.86502 17.2275C3.39462 16.9878 3.01217 16.6054 2.77248 16.135C2.5 15.6002 2.5 14.9001 2.5 13.5V12.5M14.1667 6.66667L10 2.5M10 2.5L5.83333 6.66667M10 2.5V12.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        )
    }
})

export const PhotoLibrary = defineComponent({
    setup() {
        return () => (
            <svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.40576 16.9756C6.68083 16.9756 6.13021 16.793 5.75391 16.4277C5.3776 16.0625 5.18945 15.5202 5.18945 14.8008L7.98682 12.3687C8.1473 12.2358 8.30501 12.1362 8.45996 12.0698C8.62044 11.9979 8.78092 11.9619 8.94141 11.9619C9.11849 11.9619 9.29004 11.9951 9.45605 12.0615C9.6276 12.1279 9.79362 12.2358 9.9541 12.3853L11.2905 13.5889L14.644 10.6172C14.8156 10.4622 14.9927 10.3516 15.1753 10.2852C15.3579 10.2132 15.5544 10.1772 15.7646 10.1772C15.9639 10.1772 16.1603 10.216 16.354 10.2935C16.5477 10.3654 16.7248 10.4761 16.8853 10.6255L20.7368 14.2612V14.7925C20.7368 15.5174 20.5487 16.0625 20.1724 16.4277C19.7961 16.793 19.2454 16.9756 18.5205 16.9756H7.40576ZM10.6929 10.8081C10.2059 10.8081 9.79085 10.6338 9.44775 10.2852C9.10465 9.93652 8.93311 9.51872 8.93311 9.03174C8.93311 8.55583 9.10465 8.14355 9.44775 7.79492C9.79085 7.44629 10.2059 7.27197 10.6929 7.27197C11.0138 7.27197 11.3071 7.35221 11.5728 7.5127C11.8384 7.67318 12.0487 7.889 12.2036 8.16016C12.3641 8.42578 12.4443 8.71631 12.4443 9.03174C12.4443 9.51872 12.2728 9.93652 11.9297 10.2852C11.5866 10.6338 11.1743 10.8081 10.6929 10.8081ZM3.28027 13.5723C2.41146 13.5723 1.75846 13.3592 1.32129 12.9331C0.889648 12.5015 0.673828 11.8568 0.673828 10.999V3.17139C0.673828 2.31364 0.889648 1.67171 1.32129 1.24561C1.75846 0.813965 2.41146 0.598145 3.28027 0.598145H14.6191C15.4824 0.598145 16.1326 0.813965 16.5698 1.24561C17.007 1.67725 17.2256 2.31917 17.2256 3.17139V4.98096H15.8892V3.24609C15.8892 2.81445 15.7757 2.48796 15.5488 2.2666C15.3219 2.04525 15.0037 1.93457 14.5942 1.93457H3.29688C2.88184 1.93457 2.56364 2.04525 2.34229 2.2666C2.12093 2.48796 2.01025 2.81445 2.01025 3.24609V10.9326C2.01025 11.3643 2.12093 11.6908 2.34229 11.9121C2.56364 12.1279 2.88184 12.2358 3.29688 12.2358H5.33887V13.5723H3.28027ZM7.38086 17.4155C6.51204 17.4155 5.85905 17.1997 5.42188 16.7681C4.99023 16.342 4.77441 15.7 4.77441 14.8423V7.01465C4.77441 6.1569 4.99023 5.51497 5.42188 5.08887C5.85905 4.65723 6.51204 4.44141 7.38086 4.44141H18.7197C19.583 4.44141 20.2332 4.65723 20.6704 5.08887C21.1076 5.52051 21.3262 6.16243 21.3262 7.01465V14.8423C21.3262 15.7 21.1076 16.342 20.6704 16.7681C20.2332 17.1997 19.583 17.4155 18.7197 17.4155H7.38086ZM7.39746 16.0791H18.6948C19.1043 16.0791 19.4225 15.9684 19.6494 15.7471C19.8763 15.5312 19.9897 15.2075 19.9897 14.7759V7.08936C19.9897 6.65771 19.8763 6.33122 19.6494 6.10986C19.4225 5.88851 19.1043 5.77783 18.6948 5.77783H7.39746C6.98242 5.77783 6.66423 5.88851 6.44287 6.10986C6.22152 6.33122 6.11084 6.65771 6.11084 7.08936V14.7759C6.11084 15.2075 6.22152 15.5312 6.44287 15.7471C6.66423 15.9684 6.98242 16.0791 7.39746 16.0791Z" fill="currentColor" />
            </svg>
        )
    }
})

export const PhotoIcon = defineComponent({
    setup() {
        return () => (
            <svg width="22" height="17" viewBox="0 0 22 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.41309 16.6602C2.54427 16.6602 1.89128 16.4443 1.4541 16.0127C1.02246 15.5866 0.806641 14.9447 0.806641 14.0869V5.396C0.806641 4.54378 1.02246 3.90462 1.4541 3.47852C1.89128 3.04688 2.54427 2.83105 3.41309 2.83105H5.729C6.0555 2.83105 6.29622 2.79232 6.45117 2.71484C6.60612 2.63184 6.7749 2.48796 6.95752 2.2832L7.62158 1.53613C7.83187 1.30924 8.05876 1.13493 8.30225 1.01318C8.55127 0.891439 8.88883 0.830566 9.31494 0.830566H12.627C13.0531 0.830566 13.3906 0.891439 13.6396 1.01318C13.8887 1.13493 14.1156 1.30924 14.3203 1.53613L14.9844 2.2832C15.1117 2.42155 15.2251 2.53223 15.3247 2.61523C15.4299 2.69271 15.5488 2.74805 15.6816 2.78125C15.8145 2.81445 15.9915 2.83105 16.2129 2.83105H18.5869C19.4502 2.83105 20.1004 3.04688 20.5376 3.47852C20.9748 3.90462 21.1934 4.54378 21.1934 5.396V14.0869C21.1934 14.9447 20.9748 15.5866 20.5376 16.0127C20.1004 16.4443 19.4502 16.6602 18.5869 16.6602H3.41309ZM3.42969 15.3237H18.562C18.9715 15.3237 19.2897 15.2158 19.5166 15C19.7435 14.7786 19.8569 14.4521 19.8569 14.0205V5.4707C19.8569 5.03906 19.7435 4.71533 19.5166 4.49951C19.2897 4.27816 18.9715 4.16748 18.562 4.16748H15.8809C15.5046 4.16748 15.2002 4.12598 14.9678 4.04297C14.7409 3.95443 14.5223 3.79395 14.312 3.56152L13.6646 2.83105C13.4266 2.56543 13.2163 2.38835 13.0337 2.2998C12.8511 2.21126 12.5771 2.16699 12.2119 2.16699H9.72998C9.36475 2.16699 9.09082 2.21126 8.9082 2.2998C8.72559 2.38835 8.5153 2.56543 8.27734 2.83105L7.62988 3.56152C7.4196 3.79395 7.19824 3.95443 6.96582 4.04297C6.73893 4.12598 6.43734 4.16748 6.06104 4.16748H3.42969C3.02018 4.16748 2.70199 4.27816 2.4751 4.49951C2.25374 4.71533 2.14307 5.03906 2.14307 5.4707V14.0205C2.14307 14.4521 2.25374 14.7786 2.4751 15C2.70199 15.2158 3.02018 15.3237 3.42969 15.3237ZM11 14.1284C10.3691 14.1284 9.77979 14.0122 9.23193 13.7798C8.68408 13.5474 8.20264 13.2236 7.7876 12.8086C7.37809 12.3936 7.05436 11.9121 6.81641 11.3643C6.58398 10.8164 6.46777 10.2243 6.46777 9.58789C6.46777 8.95703 6.58398 8.36768 6.81641 7.81982C7.05436 7.27197 7.37809 6.79053 7.7876 6.37549C8.20264 5.96045 8.68408 5.63672 9.23193 5.4043C9.77979 5.17188 10.3691 5.05566 11 5.05566C11.8356 5.05566 12.5965 5.25765 13.2827 5.66162C13.9689 6.06559 14.514 6.61068 14.918 7.29688C15.3219 7.98307 15.5239 8.74674 15.5239 9.58789C15.5239 10.2243 15.4077 10.8164 15.1753 11.3643C14.9429 11.9121 14.6191 12.3936 14.2041 12.8086C13.7891 13.2236 13.3076 13.5474 12.7598 13.7798C12.2119 14.0122 11.6253 14.1284 11 14.1284ZM11 12.8667C11.6032 12.8667 12.151 12.7201 12.6436 12.4268C13.1416 12.1335 13.5373 11.7406 13.8306 11.248C14.1239 10.75 14.2705 10.1966 14.2705 9.58789C14.2705 8.9847 14.1239 8.43685 13.8306 7.94434C13.5373 7.44629 13.1416 7.05062 12.6436 6.75732C12.151 6.4585 11.6032 6.30908 11 6.30908C10.3968 6.30908 9.84619 6.4585 9.34814 6.75732C8.85563 7.05062 8.45996 7.44629 8.16113 7.94434C7.86784 8.43685 7.72119 8.9847 7.72119 9.58789C7.72119 10.1966 7.86784 10.75 8.16113 11.248C8.45996 11.7406 8.85563 12.1335 9.34814 12.4268C9.84619 12.7201 10.3968 12.8667 11 12.8667ZM15.8643 6.5332C15.8643 6.25651 15.9666 6.01579 16.1714 5.81104C16.3761 5.60628 16.6224 5.50391 16.9102 5.50391C17.1868 5.50391 17.4276 5.60628 17.6323 5.81104C17.8371 6.01579 17.9395 6.25651 17.9395 6.5332C17.9395 6.82096 17.8371 7.06445 17.6323 7.26367C17.4276 7.46289 17.1868 7.5625 16.9102 7.5625C16.6224 7.5625 16.3761 7.46566 16.1714 7.27197C15.9666 7.07275 15.8643 6.8265 15.8643 6.5332Z" fill="currentColor" />
            </svg>
        )
    }
})

export const FolderIcon = defineComponent({
    setup() {
        return () => (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
            </svg>
        )
    }
})

export const ExpandIcon = defineComponent({
    setup() {
        return () => (
            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 21L21 3M3 21H9M3 21L3 15M21 3H15M21 3V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>)
    }
})
