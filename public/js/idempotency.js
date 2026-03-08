
function generateIdempotencyKey(patientId) 
{
    const timestamp = Date.now();

    const randomString = Math.random()
        .toString(36)
        .substring(2, 10);

    return `${patientId}-${timestamp}-${randomString}`;
}