export const toSimpleDate = (value: Date) => {
    return value.toISOString().split("T")[0]
}