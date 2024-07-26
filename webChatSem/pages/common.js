const isValidEmail = (email) => {
    const emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    return emailRegex.test(email);
};

const isValidPhone = (phone) => {
    const phoneRegex = /^\d{3}\s?\d{3}\s?\d{3}$/;
    return phoneRegex.test(phone);
};
