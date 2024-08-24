export const timer = (time = 1000) => {
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve('Data loaded');
        }, time);
    });
}