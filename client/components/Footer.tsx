import Image from "next/image";
import Link from "next/link";
import { FaInstagram, FaFacebook, FaTwitter, FaTiktok, FaYoutube, FaPinterest } from "react-icons/fa";

const Footer = () => {
    return (
        <footer className="bg-[#1e1e1e] dark:bg-black text-white py-10">
            <div className="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-8">
                {/* Company Info */}
                <div>
                    <h4 className="text-lg font-semibold mb-4">Company info</h4>
                    <ul className="space-y-2 text-sm">
                        <li><Link href="#">About Temu</Link></li>
                        <li><Link href="#">Temu – Shop Like a Billionaire</Link></li>
                        <li><Link href="#">Contact us</Link></li>
                        <li><Link href="#">Careers</Link></li>
                        <li><Link href="#">Press</Link></li>
                        <li><Link href="#">Temu’s Tree Planting Program</Link></li>
                    </ul>
                </div>

                {/* Customer Service */}
                <div>
                    <h4 className="text-lg font-semibold mb-4">Customer service</h4>
                    <ul className="space-y-2 text-sm">
                        <li><Link href="#">Return and refund policy</Link></li>
                        <li><Link href="#">Intellectual property policy</Link></li>
                        <li><Link href="#">Shipping info</Link></li>
                        <li><Link href="#">Report suspicious activity</Link></li>
                    </ul>
                </div>

                {/* Help */}
                <div>
                    <h4 className="text-lg font-semibold mb-4">Help</h4>
                    <ul className="space-y-2 text-sm">
                        <li><Link href="#">Support center & FAQ</Link></li>
                        <li><Link href="#">Safety center</Link></li>
                        <li><Link href="#">Temu purchase protection</Link></li>
                        <li><Link href="#">Sitemap</Link></li>
                        <li><Link href="#">Partner with Temu</Link></li>
                    </ul>
                </div>

                {/* Download the App */}
                <div>
                    <h4 className="text-lg font-semibold mb-4">Download the Temu App</h4>
                    <ul className="space-y-2 text-sm">
                        <li>📉 Price-drop alerts</li>
                        <li>⚡ Faster & more secure checkout</li>
                        <li>🎁 Exclusive offers</li>
                        <li>🚚 Track orders any time</li>
                        <li>⏳ Low stock items alerts</li>
                        <li>💰 Coupons & offers alerts</li>
                    </ul>
                    <div className="flex gap-4 mt-4">
                        <button className="bg-gray-800 p-2 rounded">📱 App Store</button>
                        <button className="bg-gray-800 p-2 rounded">📱 Google Play</button>
                    </div>
                </div>
            </div>

            {/* Security and Payment */}
            <div className="max-w-7xl mx-auto px-6 mt-10 border-t border-gray-700 pt-6 flex flex-wrap justify-between items-center">
                <div className="flex gap-4">
                    <span className="text-sm">Security certification</span>
                    <Image src="/images/security-logos.png" alt="Security" className="h-6"
                        width={100}
                        height={100} />
                </div>
                <div className="flex gap-4">
                    <span className="text-sm">We accept</span>
                    <Image src="/images/payment-logos.png" alt="Payment Methods" className="h-6"
                        width={100}
                        height={100} />
                </div>
            </div>

            {/* Social Media Icons */}
            <div className="flex justify-center gap-6 mt-6 text-xl">
                <FaInstagram />
                <FaFacebook />
                <FaTwitter />
                <FaTiktok />
                <FaYoutube />
                <FaPinterest />
            </div>

            {/* Footer Bottom */}
            <div className="text-center text-gray-500 text-sm mt-6">
                © 2025 WhaleCo Inc. | <Link href="#">Terms of use</Link> | <Link href="#">Privacy policy</Link> | <Link href="#">Your privacy choices</Link> | <Link href="#">Ad Choices</Link>
            </div>
        </footer>
    );
};

export default Footer;
