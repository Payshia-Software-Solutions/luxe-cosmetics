"use client";

import React, { useState, ChangeEvent } from "react";
import { ContactDetails } from "@/types/Checkout"; // Adjust the import path as necessary



interface ContactFormProps {
  setContactDetails: React.Dispatch<React.SetStateAction<ContactDetails>>;
}

const ContactForm: React.FC<ContactFormProps> = ({ setContactDetails }) => {
  const [email, setEmail] = useState<string>("");
  const [subscribe, setSubscribe] = useState<boolean>(false);

  const handleEmailChange = (e: ChangeEvent<HTMLInputElement>) => {
    const newEmail = e.target.value;
    setEmail(newEmail);


    setContactDetails((prevDetails) => ({
      ...prevDetails,
      email: newEmail,
    }));
  };

  const handleSubscribeChange = () => {
    const newSubscribeStatus = !subscribe;
    setSubscribe(newSubscribeStatus);

    // Update the subscription status in the parent component
    setContactDetails((prevDetails) => ({
      ...prevDetails,
      subscribe: newSubscribeStatus,
    }));
  };

  return (
    <div className="mx-auto p-6 bg-white">
      <div className="flex justify-between items-center mb-4">
        <h2 className="text-lg font-semibold">Contact</h2>
        <a href="/login" className="text-sm text-blue-600 hover:underline">
          Log in
        </a>
      </div>
      <div className="mb-4">
        <label
          htmlFor="email"
          className="block text-sm font-medium text-gray-700"
        >
          Email
        </label>
        <input
          type="email"
          id="email"
          value={email}
          onChange={handleEmailChange}
          placeholder="Enter your email"
          className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>
      <div className="flex items-center">
        <input
          type="checkbox"
          id="subscribe"
          checked={subscribe}
          onChange={handleSubscribeChange}
          className="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
        />
        <label htmlFor="subscribe" className="ml-2 text-sm text-gray-700">
          Email me with news and offers
        </label>
      </div>
    </div>
  );
};

export default ContactForm;