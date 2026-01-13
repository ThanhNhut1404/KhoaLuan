public class TestFormatter {
    public static void main(String[] args) {
        int[] arr = { 3, 7, 1, 9, 4, 6 };
        int max = arr[0];
        int min = arr[0];

        for (int i = 1; i < arr.length; i++) {
            if (arr[i] > max) {
                max = arr[i];
            }
            if (arr[i] < min) {
                min = arr[i];
            }
        }

        System.out.println("Gia tri lon nhat: " + max);
        System.out.println("Gia tri nho nhat: " + min);

        // for-each test
        for (int x : arr) {
            System.out.print(x + " ");
        }
    }
}
