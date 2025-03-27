'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { Home, Package, Percent, ShoppingCart } from 'lucide-react'; // Install lucide-react for icons

export default function Sidebar() {
    const pathname = usePathname();

    const navItems = [
        { name: 'Dashboard', href: '/', icon: Home },
        { name: 'Products', href: '/products', icon: Package },
        { name: 'Promotions', href: '/promotions', icon: Percent },
        { name: 'Orders', href: '/orders', icon: ShoppingCart },
    ];

    return (
        <aside className="w-64 bg-white shadow-md">
            <div className="p-4 text-2xl font-bold text-gray-800">Admin Panel</div>
            <nav className="mt-6">
                {navItems.map((item) => (
                    <Link
                        key={item.name}
                        href={item.href}
                        className={`flex items-center p-4 text-gray-700 hover:bg-gray-200 ${pathname === item.href ? 'bg-gray-200 font-semibold' : ''
                            }`}
                    >
                        <item.icon className="w-5 h-5 mr-3" />
                        {item.name}
                    </Link>
                ))}
            </nav>
        </aside>
    );
}