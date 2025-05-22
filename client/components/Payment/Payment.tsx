"use client";
import React from "react";
import axios from "axios";

// Define the interface for the payment details
interface PaymentDetails {
  order_id: string;
  items: string;
  currency: string;
  amount: string;
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
  address: string;
  city: string;
  country: string;
  return_url: string;
  cancel_url: string;
  notify_url: string;
}

const Payment: React.FC = () => {
  const handlePayment = async (): Promise<void> => {
    const paymentDetails: PaymentDetails = {
      order_id: "12345",
      items: "Test Item",
      currency: "LKR",
      amount: "1000",
      first_name: "John",
      last_name: "Doe",
      email: "john.doe@example.com",
      phone: "0771234567",
      address: "No.1, Main Street",
      city: "Colombo",
      country: "Sri Lanka",
      return_url: "https://kduserver.payshia.com/payment/return",
      cancel_url: "https://kduserver.payshia.com/payment/cancel",
      notify_url: "https://kduserver.payshia.com/payment/notify",
    };

    try {
      const { data } = await axios.post<string>(
        "http://localhost/TeaJarWebsite/server/payment/initiate-payment",
        paymentDetails,
        {
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
        }
      );

      // Assuming the server returns an HTML form
      document.open(); // Clear the current document
      document.write(data); // Write the new content (e.g., auto-submitting form)
      document.close(); // Complete the document loading
    } catch (error) {
      console.error("Payment initiation failed:", error);
    }
  };

  return <button onClick={handlePayment}>Pay Now</button>;
};

export default Payment;