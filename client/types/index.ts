export interface Product {
  id: string;
  name: string;
  description: string;
  price: number;
  image: string;
  category: string;
  featured: boolean;
  new: boolean;
  rating: number;
}

export interface CartItem extends Product {
  quantity: number;
}

export type Theme = 'light' | 'dark';