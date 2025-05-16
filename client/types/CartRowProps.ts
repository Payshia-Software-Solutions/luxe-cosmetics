export interface CartRowProps {
    item: {
        id: string;
        name: string;
        price: number;
        quantity: number;
        image: string;
    };
    onQuantityChange: (id: string, delta: number) => void;
    onRemove: (id: string) => void;
}